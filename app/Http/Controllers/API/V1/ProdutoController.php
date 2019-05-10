<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Produto;
use App\Models\ProdutoFoto;
use App\Models\Fornecedor;
use App\Models\PivoProdutoFornecedor;

class ProdutoController extends Controller {
    
    public function index () {
        $produtos = Produto::where('status', 1)->orderBy('nome', 'asc')->get();
        foreach ($produtos as $produto) {
            $produto->ultima_compra = $produto->ultima_compra();
        }
        return response()->json(compact('produtos'));
    }
    
    public function store(Request $request) {
        
        $produto = Produto::create($request["produto"]);
        $produto->numero_codigo_de_barras = str_pad($produto->id, 13, '0', STR_PAD_LEFT);
        $produto->save();
        $produto = Produto::find($produto->id);
        if($request["fotos"] != null) {
            foreach($request["fotos"] as $foto) {
                ProdutoFoto::create([
                    'produto_id' => $produto->id,
                    'url' => $foto['foto']
                ]);
            }
        }

        return response()->json(compact('produto')); 
    }

    public function store_update_fornecedor(Request $request) {
        $produto = Produto::find_uuid($request['produto_uuid']);
        PivoProdutoFornecedor::where('produto_id', $produto->id)->delete();
        foreach($request['fornecedores'] as $fornecedor_uuid) {
            $fornecedor = Fornecedor::find_uuid($fornecedor_uuid["fornecedor_uuid"]);
            PivoProdutoFornecedor::create([
                'produto_id'    => $produto->id, 
                'fornecedor_id' => $fornecedor->id
            ]);
        }
        return response()->json(['success'=>'Fornecedor vinculado com sucesso']);
    }

    public function show (Request $request) {
        if(is_null($request->produto_uuid)) {
            //list all
            $produto = Produto::where('status', 1)->with('foto')->get();
        } else {
            $produto = Produto::find_uuid($request->produto_uuid);
            $produto_fornecedor = $produto->fornecedores;
            $produto_foto = $produto->foto;
        }
        return response()->json(compact('produto'));
    }

    public function store_foto(Request $request) {
        $produto = Produto::find($produto->id);
        ProdutoFoto::create([
            'produto_id' => $produto->id,
            'url' => $request->foto
        ]);
        return response()->json(compact('produto')); 
    }

    public function destroy_foto (Request $request) {
        $ProdutoFoto =ProdutoFoto::find_uuid($request->uuid);
        $ProdutoFoto->delete();
        return response()->json(['sucesso'=>'imagem deletada com sucesso']);
    }

}
