<?php

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MUser', 'user');
        $this->load->model('MMovie', 'movie');
    }

    private function loadView($mainView, $data=[])
    {
        $genres = [];
        // dd($genres);
        $this->load->view('site/header', [
            'genres' => $genres
        ]);
        $this->load->view($mainView, $data);
        $this->load->view('site/footer');
    }

    public function login()
    {
        // Jika user telah login, maka arahkan ke halaman index.
        if ($this->session->has_userdata('id')) {
            return redirect('site/index');
        }

        if (!$this->input->post()) {
            // Menampilkan view login user.
            return $this->loadView('auth/login');
        }
        // Jika user sudah submit form, maka melakukan login.
        $uname = $this->input->post('username');
        $pass = $this->input->post('pass');
        // dd([$uname,$pass]);
        $user = $this->user->login($uname, $pass);
        if (empty($user)) {
            // Data user tidak ditemukan.
            flash('errlogin', 'Kombinasi username dan password tidak ditemukan dalam database. Harap periksa kembali data yang anda masukkan.', 'warning');
            return $this->loadView('auth/login');
        }
        // dd($user);
        // Data user ditemukan, set session & redirect ke site/login
        $this->session->set_userdata([
            'id' => $user['id'],
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role' => $user['role'],
        ]);
        flash('welcome', "Selamat Datang {$user['nama_lengkap']}! Nikmati Film-film menarik hanya di MOOVEE!", 'success');

        // Jika user admin maka langsung diarahkan ke web admin, jika user biasa maka diarahkan ke Home.
        $redirect_route = $user['role'] == 1 ? 'admin/index' : 'site/index';
        redirect($redirect_route);
    }

    public function register()
    {
        // Jika user telah login, maka arahkan ke halaman index.
        if ($this->session->has_userdata('id')) {
            return redirect('site/index');
        }

        if (!$this->input->post()) {
            // Menampilkan view register user.
            return $this->loadView('auth/register');
        }
        // Jika user sudah submit form., maka melakukan register
        $uname = $this->input->post('username');
        $nama_lengkap = $this->input->post('nama_lengkap');
        $pass = $this->input->post('pass');
        // dd([$uname,$pass]);
        $res = $this->user->register([
            'username' => $uname,
            'pass' => $pass,
            'nama_lengkap' => $nama_lengkap,
        ]);
        if ($res['status'] == 0) {
            // Jika Terjadi error saat register, menampilkan message error
            flash('errregister', $res['message'], 'warning');
            return $this->loadView('auth/register');
        }
        // dd($user);
        // Register berhasil, redirect ke auth/login
        flash('succregister', $res['message'], 'success'); // Set flash message berhasil.
        redirect('auth/login');
    }

    public function logout()
    {
        // Kosongkan session, redirect ke halaman login.
        $this->session->unset_userdata(['id', 'username', 'nama_lengkap', 'role']);
        redirect('auth/login');
    }
}