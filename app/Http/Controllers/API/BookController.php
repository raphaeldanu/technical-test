<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;

class BookController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $paginate = (int) $request->paginate > 0 ? $request->paginate : 25;
        $limit = (int) $request->limit ?? 0;
        if ($limit > 0) {
            return new BookCollection(Book::limit($limit)->get());
        }
        return new BookCollection(Book::paginate($paginate));
    }

    public function show(Request $request, Book $book)
    {
        return new BookResource($book);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'genre_id' => 'required',
            'judul_buku' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|numeric|between:1970,2023|digits:4',
            'sinopsis' => 'required',
        ]);

        $validated['kode_buku'] = (string) Str::uuid();

        $newBook = Book::create($validated);
        if (!$newBook) {
            return response()->json(['msg' => "Failed to save Book"]);
        }
        return response()->json([
            'msg' => "Book Saved Successfully",
            'data' => $newBook,
        ]);
    }
}
