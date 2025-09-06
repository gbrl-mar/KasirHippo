<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionAdjustmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'alasan_koreksi' => $this->reason,
            'catatan'        => $this->notes,
            'perubahan_harga'=> (int) $this->amount_change,
            'dibuat_pada'    => $this->created_at->format('d F Y, H:i'),
            'oleh_kasir'     => $this->whenLoaded('user', $this->user->name),
        ];
    }
}