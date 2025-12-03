<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminMenuItemController extends Controller
{
    public function index()
    {
        return view('admin.menu');
    }

    public function apiIndex()
    {
        $items = MenuItem::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store(MenuItemRequest $request)
    {
        $data = $request->only([
            'name',
            'description',
            'price',
            'preparation_time',
            'category_id'
        ]);

        $data['is_available'] = true;
        $data['is_featured'] = false;
        $data['calories'] = 0;
        $data['tags'] = ['new'];
        $data['rating'] = 0;
        $data['review_count'] = 0;

        if ($request->hasFile('menu_image')) {
            $path = $request->file('menu_image')->store('menu_images', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $item = MenuItem::create($data);

        return response()->json([
            'message' => 'Menu item created!',
            'item'    => $item
        ], 201);
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {
        $data = $request->only([
            'name',
            'description',
            'price',
            'preparation_time',
            'category_id'
        ]);

        if ($request->hasFile('menu_image')) {

            if ($menuItem->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_url));
            }

            $path = $request->file('menu_image')->store('menu_images', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $menuItem->update($data);

        return response()->json([
            'message' => 'Menu item updated!',
            'item'    => $menuItem
        ]);
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $menuItem->image_url));
        }

        $menuItem->delete();

        return response()->json(['message' => 'Menu item deleted']);
    }
}
