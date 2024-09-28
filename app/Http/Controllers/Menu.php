<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu as MenuModel;
use Auth;
class Menu extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all menus, including parent-child relationships
        $menus = MenuModel::with('parent')->orderBy('order', 'asc')->get();

        // Check if the user is an admin
        $isAdmin = Auth::user()->isAdmin(); // Using the isAdmin method from User model

        // Pass menus and admin status to the view
        return view('menu.index', compact('menus', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentMenus = MenuModel::whereNull('parent_id')->get();
        return view('menu.create', compact('parentMenus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'route' => 'nullable|string|max:255',
        ]);
        $menu = new MenuModel();
        $menu->title = $request->title;
        $menu->icon_class = $request->icon_class;
        $menu->parent_id = $request->parent_id;
        $menu->order = $request->order;
        $menu->route = $request->route;
        $menu->save();
        $submitSuccess = true;
        return redirect()->route('menu.index')->with('submitSuccess', $submitSuccess);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $menu = MenuModel::findOrFail($id);
        $parentMenus = MenuModel::whereNull('parent_id')->get();
        return view('menu.edit', compact('menu', 'parentMenus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'route' => 'nullable|string|max:255',
        ]);
    
        $menu = MenuModel::findOrFail($id);
    
        $menu->update([
            'title' => $request->title,
            'icon_class' => $request->icon_class,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'route' => $request->route,
        ]);
        $updateSuccess = true;
        return redirect()->route('menu.index')->with('updateSuccess', $updateSuccess);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $menu = MenuModel::findOrFail($id);
        $menu->delete();
        $DeleteSuccess = true;
        return redirect()->route('menu.index')->with('DeleteSuccess', $DeleteSuccess);
    }
}
