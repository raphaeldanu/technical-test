<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Permission\Permission;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
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
        if ($request->user()->cannot(Permission::CAN_ACCESS_BOOKS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $books = Book::all();
        $books->transform(fn($book) => [
            $book->id,
            $book->judul_buku,
            $book->penulis,
            $book->tahun_terbit,
            '<nobr>
            <a href="'.route('books.show', ['book'=>$book]).'" class="btn btn-xs btn-default text-teal mx-1 shadow p-2" title="Details">
                <i class="fa fa-lg fa-fw fa-eye"></i>
            </a>
            <a href="'.route('books.edit', ['book'=>$book]).'" class="btn btn-xs btn-default text-primary mx-1 shadow p-2" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>
            <button class="btn btn-xs btn-default text-danger mx-1 shadow p-2" data-toggle="modal" data-target="#modalDelete'.$book->kode_buku.'">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
            </nobr>'
        ]);
        $codes = Book::all();
        $codes->transform(fn($code) => [$code->kode_buku, $code->judul_buku]);

        $heads = [
            ['label' => 'ID', 'width' => 5],
            'Title',
            'Author',
            'Year',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'data' => $books->all(),
            'columns' => [null, null, null, null, ['orderable' => false]],
            'lengthMenu' => [10, 25, 50, 100],
        ];

        return view('books.index', [
            'title' => "Books",
            'heads' => $heads,
            'config' => $config,
            'kode' => $codes->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_BOOKS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $genres = Genre::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('books.create', [
            'title' => "Create New Book",
            'genres' => $genres,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        $validated['kode-buku'] = (string) Str::uuid();

        if (Book::create($validated)) {
            return redirect()->route('books.index')->with('success', 'Book Saved Successfully');
        }
        return back()->withInput()->with('danger', 'Failed to save book');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Book $book)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_BOOKS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Book $book)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_BOOKS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $genres = Genre::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('books.edit', [
            'title' => 'Edit Book',
            'genres' => $genres,
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();

        if($book->update($validated)){
            return redirect()->route('books.index')->with('success', 'Book Updated Successfully');
        }
        return back()->withInput()->with('danger', 'Failed to update book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book)
    {
        if ($request->user()->cannot(Permission::CAN_ACCESS_BOOKS)) {
            return redirect()->route('home')->with('warning', "Not Authorized");
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
}
