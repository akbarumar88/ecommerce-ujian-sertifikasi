<?php

class Site extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MMovie', 'movie');
        $this->load->model('MGenre', 'genre');
        $this->load->model('MUser', 'user');
        $this->load->model('MDiagram', 'diagram');
    }

    private function loadView($mainView, $data=[])
    {
        $genres = $this->movie->genres();
        // dd($genres);
        $this->load->view('site/header', [
            'genres' => $genres
        ]);
        $this->load->view($mainView, $data);
        $this->load->view('site/footer');
    }

    public function index()
    {
        // throw new Exception('Error gan');
        $latest_aired = $this->movie->latest_aired(); // Mengambil data film urut berdasarkan tgl rilis.
        $recently_added = $this->movie->recently_added(); // Mengambil data film yang baru ditambahkan.
        // Menampilkan view index dengan mempassing 2 data film tadi.
        $this->loadView('site/index', [
            'latest_aired' => $latest_aired,
            'recently_added' => $recently_added,
        ]);
    }

    public function search()
    {
        $q = $this->input->get('q'); // Keyword yang diinputkan user
        // Mengambil halaman saat ini. halaman ke berapa sekarang.
        $currentPage = !empty($this->input->get('p')) ? $this->input->get('p') : 1; 
        // dd($q);
        $itemPerPage = 15; // Jumlah item per halaman
        $offset = ($currentPage-1) * $itemPerPage;
        $res = $this->movie->search($q, $itemPerPage, $offset); // Mendapatkan data film berdasarkan cari keyword
        $resCount = $this->movie->searchCount($q); // Mendapatkan count data untuk paging
        $totalPage = ceil($resCount / $itemPerPage); // Menghitung total halaman

        // Menampilkan view search dan memapssing data-data yg diperlukan.
        $this->loadView('site/search', [
            'q'	=> $q,
            'res' => $res,
            'totalPage' => $totalPage,
        ]);
    }

    /**
     * Halaman Detail Film
     */
    public function movie($id)
    {
        # code...
        $res = $this->movie->find($id);
        // Melakukan pengecekan apakah user sudah login? untuk membatasi kualitas videonya.
        if (!$this->session->has_userdata('id')) {
            $res['kualitas'] = array_filter($res['kualitas'], function ($item) {
                return $item['kualitas'] == '480p';
            });
        }
        // dd($res);
        $related_movies = $this->movie->related($id);
        // dd($res);
        if ($this->session->has_userdata('id')) {
            // Tambah histori film user
            $this->user->addhistory(['iduser' => $this->session->id, 'idfilm' => $id]);
        }
        $this->loadView('site/movie', [
            'movie'	=> $res,
            'related_movies' => $related_movies,
        ]);
    }

    public function search_genre($idgenre)
    {
        $genre = $this->genre->find($idgenre);
        // Mengambil halaman saat ini. halaman ke berapa sekarang.
        $currentPage = !empty($this->input->get('p')) ? $this->input->get('p') : 1;
        // dd($genre);
        $itemPerPage = 15; // Jumlah item per halaman
        $offset = ($currentPage - 1) * $itemPerPage;
        $res = $this->movie->findByGenre([$idgenre], $itemPerPage, $offset); // Mendapatkan data film berdasarkan genre
        $resCount = $this->movie->findByGenreCount([$idgenre], $itemPerPage, $offset); // Mendapatkan count data untuk paging
        $totalPage = ceil($resCount / $itemPerPage); // Mendapatkan total page

        // Menampilkan view search dan memapssing data-data yg diperlukan.
        $this->loadView('site/search_genre', [
            'genre' => $genre['genre'],
            'res' => $res,
            'totalPage' => $totalPage,
        ]);
    }

    public function history()
    {
        $history = $this->user->history($this->session->id);
        $this->loadView('site/history', [
            'history' => $history
        ]);
    }

    public function searchAjax($q)
    {
        $res = $this->movie->search($q, 6, 0); // Mendapatkan data film berdasarkan cari keyword
        echo json_encode($res);
    }

    public function indodax()
    {
        $this->loadView('site/indodax', []);
    }

    public function previewDiagram()
    {
        $dataSample = $this->diagram->getSample();
        $this->loadView('site/sample-diagram', [
            'dataSample' => $dataSample
        ]);
        // dd($dataSample);
    }

    /**
     * Menampilkan Logo UPN beserta kata-kata
     */
    public function about()
    {
        $this->loadView('site/about');
    }
}
