<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Permission\Permission;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        $genres = Genre::all();
        
        $genres->transform(fn($genre) => [
            $genre->id,
            $genre->name, 
            '<nobr>
            <a href="'.route('genres.show', ['genre'=>$genre]).'" class="btn btn-xs btn-default text-teal mx-1 shadow p-2" title="Details">
                <i class="fa fa-lg fa-fw fa-eye"></i>
            </a>
            <a href="'.route('genres.edit', ['genre'=>$genre]).'" class="btn btn-xs btn-default text-primary mx-1 shadow p-2" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>
            <button class="btn btn-xs btn-default text-danger mx-1 shadow p-2" data-toggle="modal" data-target="#modalDelete'.$genre->id.'">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
            </nobr>',
        ]);

        $heads = [
            ['label' => 'ID', 'width' => 5],
            'Name',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'data' => $genres->all(),
            'columns' => [null, null, ['orderable' => false]],
            'lengthMenu' => [10, 25, 50, 100],
        ];

        return view('genres.index', [
            'title' => 'Genres',
            'heads' => $heads,
            'config' => $config,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        return view('genres.create', [
            'title' => "Create New Genre",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request)
    {
        $validated = $request->validated();

        if (Genre::create($validated)) {
            return redirect()->route('genres.index')->with('success', 'New Genre Saved Successfully');
        }
        return back()->withInput()->with('danger', 'Failed to save new genre');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Genre $genre)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        return view('genres.show', [
            'title' => 'Genre Details',
            'genre' => $genre,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Genre $genre)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        return view('genres.edit', [
            'title' => "Edit Genre",
            'genre' => $genre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGenreRequest  $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        if ($request->name != $genre->name) {
            $validated = $request->validated();
            if(!$genre->update($validated)){
                return back()->withInput()->with('danger', 'Failed to update Genre');
            }
        }

        return redirect()->route('genres.index')->with('success', 'Genre Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Genre $genre)
    {
        if($request->user()->cannot(Permission::CAN_ACCESS_GENRES)){
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        $genre->delete();

        return redirect()->route('genres.index')->with('success', "Genre Deleted Successfully");
    }
}
