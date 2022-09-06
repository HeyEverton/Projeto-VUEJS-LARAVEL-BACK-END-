<?php

namespace App\Http\Controllers;

use App\Api\ApiMessages;
use App\Models\Book;
use App\Models\PublishingCompany;
use Illuminate\Http\Request;

class PublishingController extends Controller
{
    public function __construct(private PublishingCompany $publishingCompany)
    {
    }

    public function index()
    {
        $companies = $this->publishingCompany->paginate(10);

        return response()->json($companies, 200);
    }

    public function pesquisarEditoraPorNome($name)
    {

        $companies = $this->publishingCompany->select()
            ->where('name', 'like', '%' . $name . '%')
            ->paginate();

        return response()->json($companies, 200);
    }

    public function pesquisarEditoraPorEndereco($address)
    {

        $companies = $this->publishingCompany->select()
            ->where('address', 'like', '%' . $address . '%')
            ->paginate();

        return response()->json($companies, 200);
    }

    public function pesquisarEditoraPorSite($website)
    {

        $companies = $this->publishingCompany->select()
            ->where('website', 'like', '%' . $website . '%')
            ->paginate();

        return response()->json($companies, 200);
    }


    public function store(Request $request)
    {
    
        $data = $request->all();

        try {

            $publishingCompany = $this->publishingCompany->create($data);
            return  response()->json([
                'data' => [
                    'msg' => 'Editora cadastrado com sucesso'
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

            $company = $this->publishingCompany->findOrFail($id);

            return response()->json([
                'data' => $company
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
    

    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            $company = $this->publishingCompany->findOrFail($id);
            $company->update($data);


            return response()->json([
                'data' => [
                    'msg' => 'Editora editada com successo',
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

            $company = $this->publishingCompany->findOrFail($id);
            $company->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Editora excluída com successo'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
