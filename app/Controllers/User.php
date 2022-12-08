<?php

namespace App\Controllers;

use App\Models\Modeluser;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    public function index()
    {
        $modelUser = new Modeluser();
        $data = $modelUser->findAll();
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
        $modelUser = new Modeluser();

        $data = $modelUser->orLike('id', $cari)->orLike('username', $cari)->get()->getResult();

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
            $modelUser = new Modeluser();
            $id = $this->request->getPost("id");
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");
            $email = $this->request->getPost("email");
            $tanggal_lahir = $this->request->getPost("tanggal_lahir");
            $nomor_telepon = $this->request->getPost("nomor_telepon");

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'id' => [
                    'rules' => 'is_unique[user.id]',
                    'label' => 'ID User',
                    'errors' => [
                        'is_unique' => "{field} sudah ada"
                    ]
                ],
                'username' => [
                    'rules' => 'is_unique[user.username]',
                    'label' => 'Username',
                    'errors' => [
                        'is_unique' => "{field} sudah ada"
                    ]
                ],
                'password' => 'required',
                'email' => 'required|valid_email',
                'tanggal_lahir' => 'required',
                'nomor_telepon' => 'required|numeric|min_length[10]|max_length[13]',
            ]);

            if(!$valid){
                $response = [
                    'status' => 404,
                    'error' => true,
                    'message' => $validation->getErrors(),
                ];

                return $this->respond($response, 404);
            }else {
                $modelUser->insert([
                    'id' => $id,
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'email' => $email,
                    'tanggal_lahir' => $tanggal_lahir,
                    'nomor_telepon' => $nomor_telepon,
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
            $model = new Modeluser();

            $data = [
                'username' => $this->request->getVar("username"),
                'email' => $this->request->getVar("email"),
                'tanggal_lahir' => $this->request->getVar("tanggal_lahir"),
                'nomor_telepon' => $this->request->getVar("nomor_telepon"),
            ];
            $data = $this->request->getRawInput();

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'username' => 'required',
                'email' => 'required|valid_email',
                'tanggal_lahir' => 'required',
                'nomor_telepon' => 'required|numeric|min_length[10]|max_length[13]',
            ]);

            if(!$valid){
                $response = [
                    'status' => 404,
                    'error' => true,
                    'message' => $validation->getErrors(),
                ];

                return $this->respond($response, 404);
            }else {
                $model->update($id, $data);
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => "Data Anda dengan ID User $id berhasil dibaharukan"
                ];
                return $this->respond($response);
            }
        }

    public function delete($id = null)
        {
            $modelUser = new Modeluser();

            $cekData = $modelUser->find($id);
            if($cekData) {
                $modelUser->delete($id);
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

        public function login()
        {
            $modelUser = new Modeluser();
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");

            $data = $modelUser->where('username', $username)->first();

            if($data) {
                $pass = $data['password'];
                $verify_pass = password_verify($password, $pass);
                if($verify_pass) {
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => 'Login Berhasil',
                        'data' => $data,
                    ];
                    return $this->respond($response, 200);
                }else {
                    return $this->failNotFound('Password salah');
                }
            }else {
                return $this->failNotFound('Username tidak ditemukan');
            }
        }

}
