<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;
use Spatie\Activitylog\Traits\LogsActivity;

class Produto extends Model {
    protected $table = 'produtos';
    use SoftDeletes;

    use LogsActivity;

    /* ******* *** LOGS *** ******* */
    protected static $logFillable = true;

    protected static $logName = 'produto';

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'uuid', 'nome', 'descricao', 'codigo_de_barras', 'qnt',
        'qnt_min', 'lote', 'valor_bruto', 'valor_banho','valor_total_custo',
        'valor_venda', 'peso', 'status', 'numero_codigo_de_barras',
        'categoria_id', 'colecao_id', 'primeira_imagem', 'caneta_rodio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "created_at", "updated_at", "deleted_at"
    ];

    //run create
    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }


    public static function find_uuid($uuid) {
       return Produto::where('uuid', $uuid)->first();
    }

    //relacionamento
    public function fornecedores() {
        return $this->belongsToMany(Fornecedor::class, 'pivo_produto_fornecedors');
    }

    public function foto() {
        return $this->hasMany(ProdutoFoto::class);
    }

    public function ultima_compra() {
        return ProdutoCompra::where('produto_id', $this->id)->orderBy('id', 'desc')->first();
    }

    public function categoria() {
        return $this->belongsTo(ProductCategory::class);
    }

    public function colecao() {
        return $this->belongsTo(ProdutoColecao::class);
    }

    public function getUltimaCompraAttribute(){
        return ProdutoCompra::where('produto_id', $this->id)
            ->orderBy('id', 'desc')
            ->first(); 
    }



    public static function remover_do_estoque($produto_id, $qnt) {
        $produto = Produto::find($produto_id);
        $produto->qnt = $produto->qnt - $qnt;
        $produto->save();
    }

    public static function adicionar_ao_estoque($produto_id, $qnt) {
        $produto = Produto::find($produto_id);
        $produto->qnt = $produto->qnt + $qnt;
        $produto->save();
    }



    

    
}
