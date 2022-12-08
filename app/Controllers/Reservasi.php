<?php

namespace App\Controllers;

use App\Models\Modelreservasi;
use CodeIgniter\RESTful\ResourceController;

class Reservasi extends ResourceController
{
    public function index()
    {
        $modelReservasi = new Modelreservasi();
        $data = $modelReservasi->findAll();
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
        $modelReservasi = new Modelreservasi();

        $data = $modelReservasi->orLike('id', $cari)->orLike('tanggal', $cari)->get()->getResult();

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
            $modelReservasi = new Modelreservasi();
            $id = $this->request->getPost("id");
            $tanggal = $this->request->getPost("tanggal");
            $sesi = $this->request->getPost("sesi");
            $dokter = $this->request->getPost("dokter");
            $keterangan = $this->request->getPost("keterangan");

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'id' => [
                    'rules' => 'is_unique[reservasi.id]',
                    'label' => 'No Reservasi',
                    'errors' => [
                        'is_unique' => "{field} sudah ada"
                    ]
                ],
                'tanggal' => [
                    'rules' => 'required',
                    'label' => 'Tanggal',
                    'errors' => [
                        'is_unique' => "{field} tidak boleh kosong !"
                    ]
                ],
                'sesi' => [
                    'rules' => 'required',
                    'label' => 'Sesi',
                    'errors' => [
                        'is_unique' => "{field} tidak boleh kosong !"
                    ]
                ],
                'dokter' => [
                    'rules' => 'required',
                    'label' => 'Dokter',
                    'errors' => [
                        'is_unique' => "{field} tidak boleh kosong !"
                    ]
                ],
                'keterangan' => [
                    'rules' => 'required',
                    'label' => 'Keterangan',
                    'errors' => [
                        'is_unique' => "{field} tidak boleh kosong !"
                    ]
                ],
            ]);

            if(!$valid){
                $response = [
                    'status' => 404,
                    'error' => true,
                    'message' => $validation->getErrors(),
                ];

                return $this->respond($response, 404);
            }else {
                $modelReservasi->insert([
                    'id' => $id,
                    'tanggal' => $tanggal,
                    'sesi' => $sesi,
                    'dokter' => $dokter,
                    'keterangan' => $keterangan,
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
            $model = new Modelreservasi();

            $data = [
                'tanggal' => $this->request->getVar("tanggal"),
                'sesi' => $this->request->getVar("sesi"),
                'dokter' => $this->request->getVar("dokter"),
                'keterangan' => $this->request->getVar("keterangan"),
            ];
            $data = $this->request->getRawInput();
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Data Anda dengan No Reservasi $id berhasil dibaharukan"
            ];
            return $this->respond($response);
        }

    public function delete($id = null)
        {
            $modelReservasi = new Modelreservasi();

            $cekData = $modelReservasi->find($id);
            if($cekData) {
                $modelReservasi->delete($id);
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
