<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     * This method returns a JSON response containing all blogs.
     */
    public function index()
    {
        $blogs = Blog::all();
        return response()->json(['blogs' => $blogs]);
    }

    /**
     * Store a newly created blog in storage.
     * This method validates the request and creates a new blog if the user has the required permission.
     */
    public function store(StoreBlogRequest $request)
    {
        if ($request->user()->can('blog.store')) {
            $blog = Blog::create($request->all());
            return response()->json(['message' => 'بلاک با موفقیت ایجاد شد', 'blog' => $blog], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Upload images for the specified blog.
     * This method validates and uploads cover and main images for the blog.
     */
    public function uploadImage(Request $request, $id)
    {
        if ($request->user()->can('blog.uploadimage')) {
            $blog = Blog::findOrFail($id);

            // Validate the uploaded images
            $validator = Validator::make($request->all(), [
                'cover_image' => 'sometimes|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
                'main_image' => 'sometimes|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $response = [];

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                if ($blog->getFirstMedia('cover_image')) {
                    $blog->clearMediaCollection('cover_image');
                }
                $media = $blog->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
                $response['cover_image'] = [
                    'name' => $media->file_name,
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                ];
            }

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                if ($blog->getFirstMedia('main_image')) {
                    $blog->clearMediaCollection('main_image');
                }
                $media = $blog->addMediaFromRequest('main_image')->toMediaCollection('main_images');
                $response['main_image'] = [
                    'name' => $media->file_name,
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                ];
            }

            return response()->json(['message' => 'تصاویر با موفقیت آپلود شدند', 'images' => $response]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید']);
        }
    }

    /**
     * Display the specified blog.
     * This method returns a JSON response containing the blog details.
     */
    public function show(Request $request, $id)
    {
        if ($request->user()->can('blog.show')) {
            $blog = Blog::findOrFail($id);
            return response()->json(['blog' => $blog]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Update the specified blog in storage.
     * This method validates the request and updates the blog if the user has the required permission.
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        if ($request->user()->can('blog.update')) {
            $blog = Blog::findOrFail($id);
            $blog->update($request->all());
            return response()->json(['message' => 'بلاگ با موفقیت به روز رسانی شد', 'blog' => $blog]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Remove the specified blog from storage.
     * This method soft deletes the blog if the user has the required permission.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('blog.destroy')) {
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return response()->json(['message' => 'بلاگ با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    /**
     * Restore a soft deleted blog.
     * This method restores the blog if the user has the required permission.
     */
    public function restore(Request $request, $id)
    {
        if ($request->user()->can('blog.restore')) {
            $blog = Blog::onlyTrashed()->findOrFail($id);
            $blog->restore();
            return response()->json(['message' => 'بلاگ با موفقیت بازیابی شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    /**
     * Permanently delete the specified blog from storage.
     * This method force deletes the blog if the user has the required permission.
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->user()->can('blog.forcedelete')) {
            $blog = Blog::onlyTrashed()->findOrFail($id);
            $blog->forceDelete();
            return response()->json(['message' => 'بلاگ با موفقیت به صورت دایمی حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }
}
