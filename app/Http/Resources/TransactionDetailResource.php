<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'nama_produk' => $this->whenLoaded('product', $this->product->name),
            'jumlah'      => (int) $this->quantity,
            'harga_satuan'=> (int) $this->price,
            'subtotal'    => (int) ($this->quantity * $this->price),
        ];
    }
}