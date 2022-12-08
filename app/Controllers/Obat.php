<?php

namespace App\Controllers;

use App\Models\Modelobat;
use CodeIgniter\RESTful\ResourceController;

class Obat extends ResourceController
{
    public function index()
    {
        $modelObat = new Modelobat();
        $data = $modelObat->findAll();
        $response = [
        'status' => 200,
        'error' => "false",
        'message' => '',
        'totaldata' => count($data),
        'data' => $data,
        ];

        return $this->respond($response, 200);
    }

    public function show($cari = null)
        {
        $modelObat = new Modelobat();

        $data = $modelObat->orLike('id', $cari)->orLike('nama', $cari)->get()->getResult();

        if(count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data, 
            ];

            return $this->respond($response, 200);
        }else if(count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];

            return $this->respond($response, 200);
        }else {
            return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
        }
    }

    public function create()
        {
            $modelObat = new Modelobat();
            $id = $this->request->getPost("id");
            $nama = $this->request->getPost("nama");
            $harga = $this->request->getPost("harga");
            $jenis = $this->request->getPost("jenis");

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'id' => [
                    'rules' => 'is_unique[obat.id]',
                    'label' => 'ID Obat',
                    'errors' => [
                        'is_unique' => "{field} sudah ada"
                    ]
                ],
                'nama' => 'required',
                'harga' => 'required|numeric',
                'jenis' => 'required',
            ]);

            if(!$valid){
                $response = [
                    'status' => 404,
                    'error' => true,
                    'message' => $validation->getErrors(),
                ];

                return $this->respond($response, 404);
            }else {
                $modelObat->insert([
                    'id' => $id,
                    'nama' => $nama,
                    'harga' => $harga,
                    'jenis' => $jenis,
                ]);

                $response = [
                    'status' => 201, 
                    'error' => "false",
                    'message' => "Data berhasil disimpan"
                ];

                return $this->respond($response, 201);
            }
        }


    public function update($id = null)
        {
            $model = new Modelobat();

            $data = [
                'nama' => $this->request->getVar("nama"),
                'harga' => $this->request->getVar("harga"),
                'jenis' => $this->request->getVar("jenis"),
            ];
            $data = $this->request->getRawInput();
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Data Anda dengan ID Obat $id berhasil dibaharukan"
            ];
            return $this->respond($response);
        }

    public function delete($id = null)
        {
            $modelObat = new Modelobat();

            $cekData = $modelObat->find($id);
            if($cekData) {
                $modelObat->delete($id);
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => "Selamat data sudah berhasil dihapus maksimal"
                ];
                return $this->respondDeleted($response);
            }else {
                return $this->failNotFound('Data tidak ditemukan kembali');
            }
        }

}
