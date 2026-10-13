<?php
// Data E-Book
$ebooks = [
    [
        'id' => 1,
        'title' => '3 Teknik Mahir Berbicara Di Depan Publik',
        'author' => 'Hebbie Agus Kurnia',
        'pages' => 32,
        'image' => 'ebook1.png',
        'pdf' => 'ebook1.pdf'
    ],
    [
        'id' => 2,
        'title' => 'Public Speaking Untuk Pemula',
        'author' => 'Rinna Raflina, S.Sos., M.I.Kom',
        'pages' => 88,
        'image' => 'ebook2.png',
        'pdf' => 'ebook2.pdf'
    ],
    [
        'id' => 3,
        'title' => 'My Public Speaking',
        'author' => 'Hilbram Dunar',
        'pages' => 180,
        'image' => 'ebook3.png',
        'pdf' => 'ebook3.pdf'
    ],
    [
        'id' => 4,
        'title' => 'Dasar Public Speaking',
        'author' => 'Dr. Mohamed Sudi, S.E., M.Si.',
        'pages' => 116,
        'image' => 'ebook4.png',
        'pdf' => 'ebook4.pdf'
    ]
];

// Fungsi pencarian ebook berdasarkan judul
function searchEbooks($query, $ebooks) {
    $query = strtolower(trim($query));
    if (empty($query)) {
        return $ebooks;
    }
    
    return array_filter($ebooks, function($ebook) use ($query) {
        return strpos(strtolower($ebook['title']), $query) !== false;
    });
}
?>
