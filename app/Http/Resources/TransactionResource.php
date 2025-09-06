<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 'this' di sini mengacu pada satu objek Transaction yang sedang diproses
        return [
            // Anda bisa mengubah nama kunci agar lebih rapi untuk frontend
            'id_transaksi'      => $this->id,
            'kode_transaksi'    => $this->transaction_code,
            'nama_pelanggan'    => $this->customer_name,

            // Format angka agar menjadi integer bersih, bukan string
            'total_harga'       => (int) $this->total_price,
            'total_dibayar'     => (int) $this->amount_paid,
            'kembalian'         => (int) $this->change_due,

            // Mengirim data yang lebih deskriptif
            'metode_pembayaran' => $this->payment_method,
            'status_pembayaran' => $this->payment_status,

            // Format tanggal agar mudah dibaca manusia
            'tanggal_transaksi' => $this->transaction_date->format('d F Y, H:i:s'),

            // Memuat data dari relasi 'user' (jika ada)
            'kasir' => $this->whenLoaded('user', function () {
    // TAMBAHKAN PENGECEKAN INI:
    // Hanya kembalikan data jika relasi 'user' tidak null.
                if ($this->user) {
                    return [
                        'id_user'   => $this->user->id_user,
                        'nama_user' => $this->user->name,
                    ];
                }
                // Jika user tidak ditemukan, kembalikan null agar tidak error
                return null; 
            }),

            // Memuat data dari relasi 'transaction_details' beserta produknya
            'detail_produk' => TransactionDetailResource::collection($this->whenLoaded('transaction_details')),
            'koreksi_pesanan' => TransactionAdjustmentResource::collection($this->whenLoaded('adjustments')),
        ];
    }
}