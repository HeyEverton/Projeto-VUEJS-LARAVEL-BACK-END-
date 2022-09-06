<?php

namespace App\Http\Controllers;

use App\Api\ApiMessages;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function __construct(private Author $author)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $author = $this->author->paginate(100000);

        return response()->json($author, 200);
    }

    public function pesquisarAutorPorNome($name)
    {

        $authors = $this->author

            ->select()
            ->where('name', 'like', '%' . $name . '%')
            ->paginate();

        return response()->json($authors, 200);
    }

    public function pesquisarAutorPorSobrenome($last_name)
    {

        $authors = $this->author->select()
        ->where('last_name', 'like', '%' . $last_name . '%')
        ->paginate();

        return response()->json($authors, 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        try {

            $author = $this->author->create($data);

            return  response()->json([
                'data' => [
                    'msg' => 'Autor cadastrado com sucesso'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $author = $this->author->findOrFail($id);

            return response()->json([
                'data' => $author
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $author = $this->author->findOrFail($id);
            $author->update($data);


            return response()->json([
                'data' => [
                    'msg' => 'Autor editado com successo',
                    'author' => $author
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage() . 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $author = $this->author->findOrFail($id);
            $author->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Autor removido com successo'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
