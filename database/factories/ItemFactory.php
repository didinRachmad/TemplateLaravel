<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    // Tentukan model yang digunakan oleh factory ini
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Field produksi_id boleh null, atau bisa diisi dengan angka acak
            'produksi_id'    => $this->faker->optional()->numberBetween(1, 10),
            // Contoh kode_item dengan format ITM-XXXX
            'kode_item'      => $this->faker->bothify('ITM-####'),
            // Nama item acak
            'nama_item'      => $this->faker->word,
            // Satuan, misal pcs, kg, liter
            'satuan'         => $this->faker->randomElement(['pcs', 'kg', 'liter']),
            // Jenis item, misal elektronik, makanan, dsb
            'jenis'          => $this->faker->word,
            // Kondisi item, misal Baik, Rusak, Bekas
            'kondisi'        => $this->faker->randomElement(['Baik', 'Rusak', 'Bekas']),
            // Kode lokasi, bisa berupa string seperti LOC-XX
            'kode_lokasi'    => $this->faker->optional()->bothify('LOC-##'),
            // Nama lokasi, misalnya nama kota
            'nama_lokasi'    => $this->faker->optional()->city,
            // Detail lokasi berupa kalimat
            'detail_lokasi'  => $this->faker->optional()->sentence,
            // Jumlah berupa angka desimal dengan 2 digit dibelakang koma
            'jumlah'         => $this->faker->randomFloat(2, 1, 1000),
            // Gambar, misal URL gambar atau nama file, di sini kita biarkan null
            'gambar'         => $this->faker->optional()->imageUrl(),
            // Approval level, defaultnya bisa diacak, kemudian di override pada test
            'approval_level' => $this->faker->numberBetween(0, 3),
            // Status, default 'Draft', bisa di override pada test
            'status'         => 'Draft',
            // Keterangan, optional
            'keterangan'     => $this->faker->optional()->sentence,
        ];
    }
}
