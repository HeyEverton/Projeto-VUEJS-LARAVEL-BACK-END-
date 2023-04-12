<?php

namespace App\Http\Controllers;

use App\Api\ApiMessages;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function __construct(private Book $book)
    {
    }


    public function index()
    {
        $books = $this->book->select()->paginate();


        return response()->json($books, 200);
    }

    public function pesquisaLivroPorTitulo($title)
    {

        $books = $this->book->select()
            ->where('title', 'like', '%' . $title . '%')
            ->paginate();

        return response()->json($books, 200);
    }


    public function store(Request $request, Book $book,)
    {
        $data = $request->json()->all();



        try {

            $book = $this->book->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $book->categories()->sync($data['categories']);
            }

            if (isset($data['authors']) && count($data['authors'])) {
                $book->authors()->sync($data['authors']);
            }

            return  response()->json([
                'data' => [
                    'msg' => 'Livro cadastrado com sucesso'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


    public function show($id)
    {
        try {

            $book = $this->book->findOrFail($id);

            return response()->json([
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function showEdit($id)
    {
        try {

            $book = $this->book
                ->with('authors')
                ->with('categories')
                ->find($id)

                // ->get()
            ;

            return response()->json([
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->json()->all();


        try {


            $book = $this->book->findOrFail($id);

            $book->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $book->categories()->sync($data['categories']);
            }

            if (isset($data['authors']) && count($data['authors'])) {
                $book->authors()->sync($data['authors']);
            }

            // $publishingCompany = $request->get('publishingCompany');
            // $book->pbcompany()->sync($publishingCompany);


            return response()->json([
                'data' => [
                    'msg' => 'Livro editado com successo',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {

            $book = $this->book->findOrFail($id);
            $book->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Livro excluído com successo'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
