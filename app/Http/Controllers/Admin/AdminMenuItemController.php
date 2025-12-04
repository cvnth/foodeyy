<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;

class AdminMenuItemController extends Controller
{
    // 1. Return the Blade View (UPDATED WITH STATS LOGIC)
    public function index()
    {
        // Count total menu items
        $totalItems = MenuItem::count();

        // Count available items (assuming 'is_available' is 1/true)
        $availableItems = MenuItem::where('is_available', true)->count();

        // Count unique categories currently being used
        $categoriesCount = MenuItem::distinct('category_id')->count('category_id');

        // Pass these variables to the view
        return view('admin.menu', compact('totalItems', 'availableItems', 'categoriesCount'));
    }

    // 2. Return JSON Data for JS to load
    public function apiIndex()
    {
        $items = MenuItem::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    // 3. Store (Create New)
    public function store(MenuItemRequest $request)
    {
        $data = $request->only([
            'name',
            'description',
            'price',
            'preparation_time',
            'category_id'
        ]);

        // Defaults
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

    // 4. Update (Edit Existing)
    public function update(MenuItemRequest $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);

        $data = $request->only([
            'name',
            'description',
            'price',
            'preparation_time',
            'category_id'
        ]);

        // Handle Image Update
        if ($request->hasFile('menu_image')) {
            // Delete old image if it exists
            if ($menuItem->image_url) {
                $oldPath = str_replace('/storage/', '', $menuItem->image_url);
                Storage::disk('public')->delete($oldPath);
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

    // 5. Delete
    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);

        if ($menuItem->image_url) {
            $oldPath = str_replace('/storage/', '', $menuItem->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $menuItem->delete();

        return response()->json(['message' => 'Menu item deleted']);
    }
}