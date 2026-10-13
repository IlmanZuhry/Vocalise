<?php
class manz{
	var $talkhost = "localhost";
    var $user = "root";
    var $pass = "";
    var $dbname = "vocalise";
	public mysqli $koneksi;
	
	function __construct(){
		$this->koneksi=mysqli_connect($this->talkhost,$this->user,$this->pass,$this->dbname);
		if(mysqli_connect_errno()){
			echo"Koneksi gagal:".mysqli_connect_error();
		}
		$this->ensureUserBioColumn();
		$this->ensureGoogleAuthColumns();
		$this->ensureCommunityPostsTable();
		$this->ensureLikesAndCommentsTable();
		$this->ensurePracticeTables();
		$this->ensureMentorTables();
		$this->ensureCameraPracticeTable();
		$this->ensureEbookTable();
		$this->ensureEbookHistoryTable();
		$this->ensureMaterialsTables();
	}

	// Generate ID unik 6 karakter (huruf kapital + angka)
	function generateId(){
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		do {
			$id = '';
			for ($i = 0; $i < 6; $i++) {
				$id .= $chars[random_int(0, strlen($chars) - 1)];
			}
			$cek = mysqli_query($this->koneksi, "SELECT Id_User FROM users WHERE Id_User = '$id'");
		} while (mysqli_num_rows($cek) > 0);
		return $id;
	}

	// Registrasi user baru
	function register($nama, $tempat_lahir, $tanggal_lahir, $username, $password){
		// Cek apakah username sudah dipakai
		$usernameEsc = mysqli_real_escape_string($this->koneksi, $username);
		$cekUser = mysqli_query($this->koneksi, "SELECT Username FROM users WHERE Username = '$usernameEsc'");
		if(mysqli_num_rows($cekUser) > 0){
			return ['status' => false, 'pesan' => 'Username sudah digunakan, coba yang lain.'];
		}

		$id            = $this->generateId();
		$namaEsc       = mysqli_real_escape_string($this->koneksi, $nama);
		$tempatEsc     = mysqli_real_escape_string($this->koneksi, $tempat_lahir);
		$tglEsc        = mysqli_real_escape_string($this->koneksi, $tanggal_lahir);
		$hashedPass    = password_hash($password, PASSWORD_BCRYPT);

		$query = "INSERT INTO users (Id_User, Nama, Tempat_Lahir, Tanggal_Lahir, Username, Password)
		          VALUES ('$id', '$namaEsc', '$tempatEsc', '$tglEsc', '$usernameEsc', '$hashedPass')";

		if(mysqli_query($this->koneksi, $query)){
			return ['status' => true, 'pesan' => 'Registrasi berhasil! Silakan login.'];
		} else {
			return ['status' => false, 'pesan' => 'Gagal menyimpan data: ' . mysqli_error($this->koneksi)];
		}
	}

	// Autentikasi user berdasarkan username dan password.
	// Mengembalikan baris user (array) jika berhasil, atau false jika gagal.
	public function authenticate($username, $password){
		$usernameEsc = mysqli_real_escape_string($this->koneksi, $username);
		$res = mysqli_query($this->koneksi, "SELECT * FROM users WHERE Username = '$usernameEsc' LIMIT 1");
		if($res && mysqli_num_rows($res) > 0){
			$row = mysqli_fetch_assoc($res);
			if(password_verify($password, $row['Password'])){
				return $row;
			}
		}
		return false;
	}

	// Ambil data user berdasarkan username (atau false jika tidak ada)
	public function getUserByUsername($username){
		$usernameEsc = mysqli_real_escape_string($this->koneksi, $username);
		$res = mysqli_query($this->koneksi, "SELECT * FROM users WHERE Username = '$usernameEsc' LIMIT 1");
		if($res && mysqli_num_rows($res) > 0){
			return mysqli_fetch_assoc($res);
		}
		return false;
	}

	// Atur ulang kata sandi setelah data identitas dasar pengguna terverifikasi.
	public function resetPasswordByIdentity($username, $tempatLahir, $tanggalLahir, $newPassword){
		$stmt = mysqli_prepare($this->koneksi, 'SELECT Id_User FROM users WHERE Username = ? AND Tempat_Lahir = ? AND Tanggal_Lahir = ? LIMIT 1');
		if (!$stmt) return false;

		mysqli_stmt_bind_param($stmt, 'sss', $username, $tempatLahir, $tanggalLahir);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$user = $result ? mysqli_fetch_assoc($result) : null;
		mysqli_stmt_close($stmt);

		if (!$user) return false;

		$passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
		$update = mysqli_prepare($this->koneksi, 'UPDATE users SET Password = ? WHERE Id_User = ?');
		if (!$update) return false;
		mysqli_stmt_bind_param($update, 'ss', $passwordHash, $user['Id_User']);
		$success = mysqli_stmt_execute($update);
		mysqli_stmt_close($update);
		return $success;
	}

	// Cari akun untuk tahap verifikasi pemulihan kata sandi. Tidak mengubah data akun.
	public function getUserIdByIdentity($username, $tempatLahir, $tanggalLahir){
		$stmt = mysqli_prepare($this->koneksi, 'SELECT Id_User FROM users WHERE Username = ? AND Tempat_Lahir = ? AND Tanggal_Lahir = ? LIMIT 1');
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, 'sss', $username, $tempatLahir, $tanggalLahir);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$user = $result ? mysqli_fetch_assoc($result) : null;
		mysqli_stmt_close($stmt);
		return $user['Id_User'] ?? false;
	}

	// Hanya kolom Password yang diperbarui; data pendaftaran lainnya tetap utuh.
	public function updatePasswordByUserId($userId, $newPassword){
		$passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
		$stmt = mysqli_prepare($this->koneksi, 'UPDATE users SET Password = ? WHERE Id_User = ?');
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, 'ss', $passwordHash, $userId);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
	}

	// Ambil pengguna yang telah ditautkan ke akun Google tertentu.
	public function getUserByGoogleSub($googleSub){
		$googleSubEsc = mysqli_real_escape_string($this->koneksi, $googleSub);
		$res = mysqli_query($this->koneksi, "SELECT * FROM users WHERE Google_Sub = '$googleSubEsc' LIMIT 1");
		if ($res && mysqli_num_rows($res) > 0) {
			return mysqli_fetch_assoc($res);
		}
		return false;
	}

	// Buat akun lokal untuk pengguna yang baru pertama kali masuk dengan Google.
	public function createGoogleUser($googleSub, $email, $name, $photo = ''){
		$googleSubEsc = mysqli_real_escape_string($this->koneksi, $googleSub);
		$emailEsc = mysqli_real_escape_string($this->koneksi, $email);
		$nameEsc = mysqli_real_escape_string($this->koneksi, $name ?: 'Pengguna Google');
		$photoEsc = mysqli_real_escape_string($this->koneksi, $photo);
		$baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', strstr($email, '@', true) ?: 'googleuser');
		$baseUsername = substr($baseUsername ?: 'googleuser', 0, 42);
		$username = $baseUsername;
		$number = 1;
		do {
			$usernameEsc = mysqli_real_escape_string($this->koneksi, $username);
			$exists = mysqli_query($this->koneksi, "SELECT 1 FROM users WHERE Username = '$usernameEsc' LIMIT 1");
			if (!$exists || mysqli_num_rows($exists) === 0) break;
			$number++;
			$username = substr($baseUsername, 0, 50 - strlen((string) $number)) . $number;
		} while ($number < 10000);

		$id = $this->generateId();
		$randomPassword = password_hash(bin2hex(random_bytes(32)), PASSWORD_BCRYPT);
		$sql = "INSERT INTO users (Id_User, Nama, Tempat_Lahir, Tanggal_Lahir, Username, Password, Foto, Google_Sub, Email)
			VALUES ('$id', '$nameEsc', '', '1970-01-01', '$usernameEsc', '$randomPassword', '$photoEsc', '$googleSubEsc', '$emailEsc')";
		if (!mysqli_query($this->koneksi, $sql)) return false;
		return $this->getUserById($id);
	}

	// Set variabel session PHP dari data user
	public function setSessionFromUser($row){
		if (session_status() == PHP_SESSION_NONE) session_start();
		$_SESSION['name'] = $row['Nama'];
		$_SESSION['username'] = $row['Username'];
		$_SESSION['user_id'] = $row['Id_User'];
		$_SESSION['foto'] = $row['Foto'] ?? '';
		$_SESSION['bio'] = $row['Bio'] ?? '';
	}

	// Bantuan logout (menghapus session)
	public function logout(){
		if (session_status() == PHP_SESSION_NONE) session_start();
		session_unset();
		session_destroy();
	}

	// Periksa apakah sudah login
	public function isLoggedIn(){
		if (session_status() == PHP_SESSION_NONE) session_start();
		return !empty($_SESSION['user_id']);
	}

	// Pastikan session dimulai (dipanggil sebelum output)
	public function ensureSession(){
		if (session_status() == PHP_SESSION_NONE) session_start();
	}

	// Kembalikan data user saat ini dari session (atau false jika tidak ada)
	public function getCurrentUser(){
		$this->ensureSession();
		if (!empty($_SESSION['user_id'])){
			return [
				'Id_User' => $_SESSION['user_id'] ?? null,
				'Nama' => $_SESSION['name'] ?? null,
				'Username' => $_SESSION['username'] ?? null,
				'Foto' => $_SESSION['foto'] ?? '',
				'Bio' => $_SESSION['bio'] ?? '',
			];
		}
		return false;
	}

	// Kembalikan nama yang aman untuk ditampilkan
	public function getDisplayName(){
		$user = $this->getCurrentUser();
		if ($user !== false && !empty($user['Nama'])) return htmlspecialchars($user['Nama']);
		return 'User';
	}

	// Kembalikan username yang aman untuk ditampilkan
	public function getDisplayUsername(){
		$user = $this->getCurrentUser();
		if ($user !== false && !empty($user['Username'])) return htmlspecialchars($user['Username']);
		return '';
	}

	// Kembalikan URL foto profil (atau kosong jika belum ada)
	public function getDisplayFoto(){
		$user = $this->getCurrentUser();
		if ($user !== false && !empty($user['Foto'])) return htmlspecialchars($user['Foto']);
		return '';
	}

	public function getDisplayBio(){
		$user = $this->getCurrentUser();
		if ($user !== false && !empty($user['Bio'])) return htmlspecialchars($user['Bio']);
		return 'yang penting bicara aja dulu';
	}

	// Ambil data user berdasarkan Id_User
	public function getUserById($userId){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$res = mysqli_query($this->koneksi, "SELECT * FROM users WHERE Id_User = '$userIdEsc' LIMIT 1");
		if($res && mysqli_num_rows($res) > 0){
			return mysqli_fetch_assoc($res);
		}
		return false;
	}

	// Update profil user (nama, username, foto, bio)
	public function updateProfile($userId, $nama, $username, $fotoPath = null, $bio = null){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$namaEsc = mysqli_real_escape_string($this->koneksi, $nama);
		$usernameEsc = mysqli_real_escape_string($this->koneksi, $username);
		$bioEsc = $bio !== null ? mysqli_real_escape_string($this->koneksi, $bio) : null;

		// Cek apakah username sudah dipakai oleh user lain
		$cekUser = mysqli_query($this->koneksi, "SELECT Id_User FROM users WHERE Username = '$usernameEsc' AND Id_User != '$userIdEsc'");
		if(mysqli_num_rows($cekUser) > 0){
			return ['status' => false, 'pesan' => 'Username sudah digunakan oleh user lain.'];
		}

		$fields = [
			"Nama='$namaEsc'",
			"Username='$usernameEsc'",
		];

		if ($bio !== null) {
			$fields[] = "Bio='$bioEsc'";
		}

		if ($fotoPath !== null) {
			$fotoEsc = mysqli_real_escape_string($this->koneksi, $fotoPath);
			$fields[] = "Foto='$fotoEsc'";
		}

		$sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE Id_User='$userIdEsc'";

		if(mysqli_query($this->koneksi, $sql)){
			// Refresh session data
			$updatedUser = $this->getUserById($userId);
			if ($updatedUser) {
				$this->setSessionFromUser($updatedUser);
			}
			return ['status' => true, 'pesan' => 'Profil berhasil diperbarui.'];
		} else {
			return ['status' => false, 'pesan' => 'Gagal memperbarui profil: ' . mysqli_error($this->koneksi)];
		}
	}

	private function ensureUserBioColumn(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'users'");
		if (!$table || mysqli_num_rows($table) === 0) {
			return;
		}

		$check = mysqli_query($this->koneksi, "SHOW COLUMNS FROM users LIKE 'Bio'");
		if ($check && mysqli_num_rows($check) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE users ADD COLUMN Bio VARCHAR(160) NOT NULL DEFAULT 'yang penting bicara aja dulu' AFTER Foto");
		}
	}

	private function ensureGoogleAuthColumns(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'users'");
		if (!$table || mysqli_num_rows($table) === 0) return;

		$googleSub = mysqli_query($this->koneksi, "SHOW COLUMNS FROM users LIKE 'Google_Sub'");
		if ($googleSub && mysqli_num_rows($googleSub) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE users ADD COLUMN Google_Sub VARCHAR(255) NULL UNIQUE AFTER Password");
		}
		$email = mysqli_query($this->koneksi, "SHOW COLUMNS FROM users LIKE 'Email'");
		if ($email && mysqli_num_rows($email) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE users ADD COLUMN Email VARCHAR(255) NULL AFTER Google_Sub");
		}
	}

	// Pastikan tabel komunitas ada di database
	public function ensureCommunityPostsTable(){
	$create = "CREATE TABLE IF NOT EXISTS Komunitas (
		Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		Id_User VARCHAR(6) NOT NULL,
		Isi TEXT NOT NULL,
		Dibuat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		Update_Post DATETIME NULL DEFAULT NULL
		ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (Id),

		KEY idx_user_id (Id_User),

		CONSTRAINT fk_community_user FOREIGN KEY (Id_User) REFERENCES users(Id_User)
		ON DELETE CASCADE
		ON UPDATE CASCADE

	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
	mysqli_query($this->koneksi, $create);
}

	public function getCommunityPosts(){
	$sql = "SELECT p.Id,p.Id_User,p.Isi,p.Dibuat,
		u.Nama AS name,u.Username AS username,u.Foto AS foto
		FROM Komunitas p LEFT JOIN users u ON p.Id_User = u.Id_User ORDER BY p.Dibuat DESC";

	$res = mysqli_query($this->koneksi, $sql);

	$posts = [];
	if ($res){
		while ($row = mysqli_fetch_assoc($res)){
			$posts[] = $row;
		}
	}
	return $posts;
}

	public function getCommunityPostById($postId){
	$postIdEsc = (int) $postId;
	$sql = "SELECT Id,Id_User,Isi,Dibuat
		FROM Komunitas WHERE Id = $postIdEsc LIMIT 1";
	$res = mysqli_query($this->koneksi, $sql);

	if ($res && mysqli_num_rows($res) > 0){
		return mysqli_fetch_assoc($res);
	}
	return false;
}

	public function createCommunityPost($userId, $content){
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
	$contentEsc = mysqli_real_escape_string($this->koneksi, $content);
	$sql = "INSERT INTO Komunitas (Id_User, Isi) VALUES ('$userIdEsc', '$contentEsc')";
	return mysqli_query($this->koneksi, $sql);
}

	public function updateCommunityPost($postId, $userId,$content){
	$postIdEsc = (int) $postId;
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
	$contentEsc = mysqli_real_escape_string($this->koneksi, $content);
	$sql = "UPDATE Komunitas SET Isi = '$contentEsc'
		WHERE Id = $postIdEsc
		AND Id_User = '$userIdEsc'
		LIMIT 1";
	return mysqli_query($this->koneksi, $sql);
}
	public function deleteCommunityPost($postId, $userId){
	$postIdEsc = (int) $postId;
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
	$sql = "DELETE FROM Komunitas WHERE Id = $postIdEsc AND Id_User = '$userIdEsc' LIMIT 1";
	return mysqli_query($this->koneksi, $sql);
}

	// Pastikan tabel likes dan comments ada di database
	public function ensureLikesAndCommentsTable(){
	// Tabel untuk likes
	$createLikes = "CREATE TABLE IF NOT EXISTS post_likes (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		post_id INT UNSIGNED NOT NULL,
		user_id VARCHAR(6) NOT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY unique_like (post_id, user_id),
		KEY idx_post (post_id),
		KEY idx_user (user_id),
		CONSTRAINT fk_like_post FOREIGN KEY (post_id) REFERENCES Komunitas(Id) ON DELETE CASCADE,
		CONSTRAINT fk_like_user FOREIGN KEY (user_id) REFERENCES users(Id_User) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
	mysqli_query($this->koneksi, $createLikes);

	// Tabel untuk comments
	$createComments = "CREATE TABLE IF NOT EXISTS post_comments (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		post_id INT UNSIGNED NOT NULL,
		user_id VARCHAR(6) NOT NULL,
		content TEXT NOT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_post (post_id),
		KEY idx_user (user_id),
		CONSTRAINT fk_comment_post FOREIGN KEY (post_id) REFERENCES Komunitas(Id) ON DELETE CASCADE,
		CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users(Id_User) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
	mysqli_query($this->koneksi, $createComments);
}

	// Toggle like (add or remove)
	public function toggleLike($postId, $userId){
	$postIdEsc = (int) $postId;
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);

	// Cek apakah sudah like
	$cek = mysqli_query($this->koneksi, "SELECT id FROM post_likes WHERE post_id = $postIdEsc AND user_id = '$userIdEsc'");

	if (mysqli_num_rows($cek) > 0) {
		// Jika sudah like, hapus
		$sql = "DELETE FROM post_likes WHERE post_id = $postIdEsc AND user_id = '$userIdEsc'";
		return mysqli_query($this->koneksi, $sql);
	} else {
		// Jika belum, tambah
		$sql = "INSERT INTO post_likes (post_id, user_id) VALUES ($postIdEsc, '$userIdEsc')";
		return mysqli_query($this->koneksi, $sql);
	}
}

	// Ambil jumlah like
	public function getLikeCount($postId){
	$postIdEsc = (int) $postId;
	$sql = "SELECT COUNT(*) as count FROM post_likes WHERE post_id = $postIdEsc";
	$res = mysqli_query($this->koneksi, $sql);
	if ($res) {
		$row = mysqli_fetch_assoc($res);
		return (int) $row['count'];
	}
	return 0;
}

	// Cek apakah user sudah like
	public function getUserLikeStatus($postId, $userId){
	if (empty($userId)) return false;
	$postIdEsc = (int) $postId;
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
	$sql = "SELECT id FROM post_likes WHERE post_id = $postIdEsc AND user_id = '$userIdEsc' LIMIT 1";
	$res = mysqli_query($this->koneksi, $sql);
	return mysqli_num_rows($res) > 0;
}

	// Tambah comment
	public function addComment($postId, $userId, $content){
	$postIdEsc = (int) $postId;
	$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
	$contentEsc = mysqli_real_escape_string($this->koneksi, trim($content));

	if (empty($contentEsc)) return false;

	$sql = "INSERT INTO post_comments (post_id, user_id, content) VALUES ($postIdEsc, '$userIdEsc', '$contentEsc')";
	return mysqli_query($this->koneksi, $sql);
}

	// Ambil jumlah comment
	public function getCommentCount($postId){
	$postIdEsc = (int) $postId;
	$sql = "SELECT COUNT(*) as count FROM post_comments WHERE post_id = $postIdEsc";
	$res = mysqli_query($this->koneksi, $sql);
	if ($res) {
		$row = mysqli_fetch_assoc($res);
		return (int) $row['count'];
	}
	return 0;
}

	public function getComments($postId){
	$postIdEsc = (int) $postId;
	$sql = "SELECT
		pc.id,
		pc.content,
		pc.created_at,

		u.Nama,
		u.Username,
		u.Id_User,
		u.Foto

		FROM post_comments pc
		JOIN users u ON pc.user_id = u.Id_User
		WHERE pc.post_id = $postIdEsc ORDER BY pc.created_at ASC";

	$res = mysqli_query($this->koneksi, $sql);
	$comments = [];
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			$comments[] = $row;
		}
	}

	return $comments;
}

	public function getCommunityPostHistory($userId, $limit = 10){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$sql = "SELECT Id, Isi, Dibuat FROM Komunitas WHERE Id_User = '$userIdEsc' ORDER BY Dibuat DESC LIMIT $limit";
		$res = mysqli_query($this->koneksi, $sql);
		$history = [];
		if ($res){
			while ($row = mysqli_fetch_assoc($res)){
				$history[] = $row;
			}
		}
		return $history;
	}

	public function getUserCommentHistory($userId, $limit = 10){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$sql = "SELECT pc.id, pc.content, pc.post_id, pc.created_at, k.Isi AS post_content
			FROM post_comments pc
			LEFT JOIN Komunitas k ON pc.post_id = k.Id
			WHERE pc.user_id = '$userIdEsc'
			ORDER BY pc.created_at DESC
			LIMIT $limit";
		$res = mysqli_query($this->koneksi, $sql);
		$history = [];
		if ($res){
			while ($row = mysqli_fetch_assoc($res)){
				$history[] = $row;
			}
		}
		return $history;
	}

	public function getMaterialActivityHistory($userId, $limit = 10){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$sql = "SELECT p.created_at, v.title AS video_title, m.title AS material_title
			FROM material_video_progress p
			JOIN material_videos v ON p.video_id = v.id
			JOIN materials m ON v.material_id = m.id
			WHERE p.user_id = '$userIdEsc'
			ORDER BY p.created_at DESC
			LIMIT $limit";
		$res = mysqli_query($this->koneksi, $sql);
		$history = [];
		if ($res){
			while ($row = mysqli_fetch_assoc($res)){
				$history[] = $row;
			}
		}
		return $history;
	}

	public function getLatestCommentReplies($userId, $limit = 3){
		// Get latest comments on user's posts (replies to user's posts)
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$sql = "SELECT 
			pc.id,
			pc.content,
			pc.user_id,
			pc.post_id,
			pc.created_at,
			u.Nama,
			k.Isi as post_content
		FROM post_comments pc
		JOIN users u ON pc.user_id = u.Id_User
		JOIN komunitas k ON pc.post_id = k.Id
		WHERE k.Id_User = '$userIdEsc'
		ORDER BY pc.created_at DESC
		LIMIT $limit";
		
		$res = mysqli_query($this->koneksi, $sql);
		$comments = [];
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$comments[] = $row;
			}
		}
		return $comments;
	}

	public function ensurePracticeTables(){
		$this->ensurePracticeHistoryTable();
		$this->ensureChallengeHistoryTable();
		$this->ensureAiFeedbackTable();
	}

	public function ensureMentorTables(){
		$this->ensureMentorAccountsTable();
		$this->ensureMentorSubmissionsTable();
		$this->ensureMentorReviewsTable();
	}

	public function ensureMentorAccountsTable(){
		$sql = "CREATE TABLE IF NOT EXISTS mentor_accounts (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(120) NOT NULL,
			username VARCHAR(60) NOT NULL,
			password_hash VARCHAR(255) NOT NULL,
			specialty VARCHAR(30) NOT NULL DEFAULT 'voice',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY unique_mentor_username (username),
			UNIQUE KEY unique_mentor_specialty (specialty)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
		$this->ensureMentorSpecialtyColumn();
	}

	private function ensureMentorSpecialtyColumn(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'mentor_accounts'");
		if (!$table || mysqli_num_rows($table) === 0) return;
		$check = mysqli_query($this->koneksi, "SHOW COLUMNS FROM mentor_accounts LIKE 'specialty'");
		if ($check && mysqli_num_rows($check) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE mentor_accounts ADD COLUMN specialty VARCHAR(30) NOT NULL DEFAULT 'voice' AFTER password_hash");
		}
	}

	public function ensureMentorSubmissionsTable(){
		$sql = "CREATE TABLE IF NOT EXISTS mentor_submissions (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			practice_history_id INT UNSIGNED NOT NULL,
			user_id VARCHAR(6) NOT NULL,
			mentor_id INT UNSIGNED NULL,
			feature_type VARCHAR(30) NOT NULL DEFAULT 'voice',
			status VARCHAR(30) NOT NULL DEFAULT 'pending',
			submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			reviewed_at DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY unique_practice_submission (practice_history_id),
			KEY idx_mentor_submission_user (user_id),
			KEY idx_mentor_submission_mentor (mentor_id),
			KEY idx_mentor_submission_status (status),
			KEY idx_mentor_submission_feature (feature_type),
			CONSTRAINT fk_mentor_submission_practice FOREIGN KEY (practice_history_id) REFERENCES practice_history(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT fk_mentor_submission_user FOREIGN KEY (user_id) REFERENCES users(Id_User)
			ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT fk_mentor_submission_mentor FOREIGN KEY (mentor_id) REFERENCES mentor_accounts(id)
			ON DELETE SET NULL ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
		$this->ensureSubmissionFeatureTypeColumn();
	}

	private function ensureSubmissionFeatureTypeColumn(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'mentor_submissions'");
		if (!$table || mysqli_num_rows($table) === 0) return;
		$check = mysqli_query($this->koneksi, "SHOW COLUMNS FROM mentor_submissions LIKE 'feature_type'");
		if ($check && mysqli_num_rows($check) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE mentor_submissions ADD COLUMN feature_type VARCHAR(30) NOT NULL DEFAULT 'voice' AFTER mentor_id");
		}
		try {
			mysqli_query($this->koneksi, "ALTER TABLE mentor_submissions DROP FOREIGN KEY fk_mentor_submission_practice");
		} catch (Exception $e) {
			// Foreign key might not exist, ignore
		}
		// Fix unique key: must be composite (practice_history_id, feature_type) to support
		// different feature types with same IDs from different tables
		$indexCheck = mysqli_query($this->koneksi, "SHOW INDEX FROM mentor_submissions WHERE Key_name = 'unique_practice_submission'");
		if ($indexCheck && mysqli_num_rows($indexCheck) > 0) {
			// Check if it's already composite
			$columns = [];
			while ($idx = mysqli_fetch_assoc($indexCheck)) {
				$columns[] = $idx['Column_name'];
			}
			if (!in_array('feature_type', $columns)) {
				mysqli_query($this->koneksi, "ALTER TABLE mentor_submissions DROP INDEX unique_practice_submission");
				mysqli_query($this->koneksi, "ALTER TABLE mentor_submissions ADD UNIQUE KEY unique_practice_feature (practice_history_id, feature_type)");
			}
		}
	}

	public function ensureMentorReviewsTable(){
		$sql = "CREATE TABLE IF NOT EXISTS mentor_reviews (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			submission_id INT UNSIGNED NOT NULL,
			mentor_id INT UNSIGNED NOT NULL,
			articulation_score INT UNSIGNED NOT NULL,
			fluency_score INT UNSIGNED NOT NULL,
			confidence_score INT UNSIGNED NOT NULL,
			structure_score INT UNSIGNED NOT NULL,
			intonation_score INT UNSIGNED NOT NULL,
			final_score INT UNSIGNED NOT NULL,
			strengths TEXT NOT NULL,
			improvements TEXT NOT NULL,
			feedback TEXT NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY unique_submission_review (submission_id),
			KEY idx_mentor_review_mentor (mentor_id),
			CONSTRAINT fk_mentor_review_submission FOREIGN KEY (submission_id) REFERENCES mentor_submissions(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT fk_mentor_review_mentor FOREIGN KEY (mentor_id) REFERENCES mentor_accounts(id)
			ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
	}

	public function hasMentorAccounts(){
		$res = mysqli_query($this->koneksi, "SELECT id FROM mentor_accounts LIMIT 1");
		return $res && mysqli_num_rows($res) > 0;
	}

	public function createMentorAccount($name, $username, $password, $specialty = 'voice'){
		$validSpecialties = ['voice', 'challenge', 'camera'];
		if (!in_array($specialty, $validSpecialties)) $specialty = 'voice';
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO mentor_accounts (name, username, password_hash, specialty) VALUES (?, ?, ?, ?)");
		if (!$stmt) return false;
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);
		mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $passwordHash, $specialty);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $saved;
	}

	public function authenticateMentor($username, $password){
		$stmt = mysqli_prepare($this->koneksi, "SELECT id, name, username, password_hash, specialty FROM mentor_accounts WHERE username = ? LIMIT 1");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = $result ? mysqli_fetch_assoc($result) : false;
		mysqli_stmt_close($stmt);

		if ($row && password_verify($password, $row['password_hash'])) {
			unset($row['password_hash']);
			return $row;
		}

		return false;
	}

	public function setMentorSession($mentor){
		$this->ensureSession();
		$_SESSION['mentor_id'] = (int) $mentor['id'];
		$_SESSION['mentor_name'] = $mentor['name'];
		$_SESSION['mentor_username'] = $mentor['username'];
		$_SESSION['mentor_specialty'] = $mentor['specialty'] ?? 'voice';
	}

	public function getCurrentMentor(){
		$this->ensureSession();
		if (empty($_SESSION['mentor_id'])) return false;

		return [
			'id' => (int) $_SESSION['mentor_id'],
			'name' => $_SESSION['mentor_name'] ?? 'Mentor',
			'username' => $_SESSION['mentor_username'] ?? '',
			'specialty' => $_SESSION['mentor_specialty'] ?? 'voice'
		];
	}

	public function getMentorDashboardStats($specialty = null){
		$stats = [
			'pending' => 0,
			'reviewed' => 0,
			'students' => 0,
			'practice_audio' => 0,
			'average_score' => 0
		];

		$featureFilter = '';
		if ($specialty) {
			$specEsc = mysqli_real_escape_string($this->koneksi, $specialty);
			$featureFilter = " WHERE ms.feature_type = '$specEsc'";
		}

		$res = mysqli_query($this->koneksi, "SELECT
			SUM(CASE WHEN ms.status = 'pending' THEN 1 ELSE 0 END) AS pending_total,
			SUM(CASE WHEN ms.status = 'reviewed' THEN 1 ELSE 0 END) AS reviewed_total,
			COUNT(DISTINCT ms.user_id) AS student_total
			FROM mentor_submissions ms $featureFilter");
		if ($res && $row = mysqli_fetch_assoc($res)) {
			$stats['pending'] = (int) ($row['pending_total'] ?? 0);
			$stats['reviewed'] = (int) ($row['reviewed_total'] ?? 0);
			$stats['students'] = (int) ($row['student_total'] ?? 0);
		}

		if ($specialty === 'challenge') {
			$practiceRes = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM speaking_challenge_history");
		} else {
			$practiceRes = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM practice_history");
		}
		if ($practiceRes && $row = mysqli_fetch_assoc($practiceRes)) {
			$stats['practice_audio'] = (int) $row['total'];
		}

		$scoreFilter = '';
		if ($specialty) {
			$specEsc = mysqli_real_escape_string($this->koneksi, $specialty);
			$scoreFilter = " WHERE mr.submission_id IN (SELECT id FROM mentor_submissions WHERE feature_type = '$specEsc')";
		}
		$scoreRes = mysqli_query($this->koneksi, "SELECT AVG(mr.final_score) AS average_score FROM mentor_reviews mr $scoreFilter");
		if ($scoreRes && $row = mysqli_fetch_assoc($scoreRes)) {
			$stats['average_score'] = (int) round((float) ($row['average_score'] ?? 0));
		}

		return $stats;
	}

	public function getMentorReviewQueue($limit = 12, $specialty = null){
		$items = [];
		$limit = max(1, (int) $limit);
		$featureFilter = '';
		if ($specialty) {
			$specEsc = mysqli_real_escape_string($this->koneksi, $specialty);
			$featureFilter = " AND ms.feature_type = '$specEsc'";
		}
		$sql = "SELECT
			ms.id,
			ms.status,
			ms.feature_type,
			ms.submitted_at,
			ms.reviewed_at,
			COALESCE(ph.topic, ch.challenge_type, cp.topic) AS topic,
			COALESCE(ph.duration_seconds, ch.actual_seconds, cp.duration_seconds) AS duration_seconds,
			COALESCE(ph.audio_path, ch.audio_path) AS audio_path,
			cp.video_path,
			u.Nama AS student_name,
			u.Username AS student_username,
			mr.final_score
			FROM mentor_submissions ms
			LEFT JOIN practice_history ph ON ph.id = ms.practice_history_id AND ms.feature_type = 'voice'
			LEFT JOIN speaking_challenge_history ch ON ch.id = ms.practice_history_id AND ms.feature_type = 'challenge'
			LEFT JOIN camera_practice_history cp ON cp.id = ms.practice_history_id AND ms.feature_type = 'camera'
			JOIN users u ON u.Id_User = ms.user_id
			LEFT JOIN mentor_reviews mr ON mr.submission_id = ms.id
			WHERE 1=1 $featureFilter
			ORDER BY FIELD(ms.status, 'pending', 'revision_requested', 'reviewed'), ms.submitted_at DESC
			LIMIT $limit";
		$res = mysqli_query($this->koneksi, $sql);
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$items[] = $row;
			}
		}
		return $items;
	}

	public function getMentorSubmissionById($submissionId){
		$submissionId = (int) $submissionId;
		$sql = "SELECT
			ms.id,
			ms.status,
			ms.feature_type,
			ms.submitted_at,
			ms.reviewed_at,
			COALESCE(ph.topic, ch.challenge_type, cp.topic) AS topic,
			COALESCE(ph.duration_seconds, ch.actual_seconds, cp.duration_seconds) AS duration_seconds,
			COALESCE(ph.audio_path, ch.audio_path) AS audio_path,
			cp.video_path,
			COALESCE(ph.created_at, ch.created_at, cp.created_at) AS practice_created_at,
			u.Id_User AS student_id,
			u.Nama AS student_name,
			u.Username AS student_username,
			mr.articulation_score,
			mr.fluency_score,
			mr.confidence_score,
			mr.structure_score,
			mr.intonation_score,
			mr.final_score,
			mr.strengths,
			mr.improvements,
			mr.feedback
			FROM mentor_submissions ms
			LEFT JOIN practice_history ph ON ph.id = ms.practice_history_id AND ms.feature_type = 'voice'
			LEFT JOIN speaking_challenge_history ch ON ch.id = ms.practice_history_id AND ms.feature_type = 'challenge'
			LEFT JOIN camera_practice_history cp ON cp.id = ms.practice_history_id AND ms.feature_type = 'camera'
			JOIN users u ON u.Id_User = ms.user_id
			LEFT JOIN mentor_reviews mr ON mr.submission_id = ms.id
			WHERE ms.id = $submissionId
			LIMIT 1";
		$res = mysqli_query($this->koneksi, $sql);
		return $res && mysqli_num_rows($res) > 0 ? mysqli_fetch_assoc($res) : false;
	}

	public function saveMentorReview($submissionId, $mentorId, $scores, $strengths, $improvements, $feedback){
		$submissionId = (int) $submissionId;
		$mentorId = (int) $mentorId;
		$articulation = max(0, min(100, (int) ($scores['articulation'] ?? 0)));
		$fluency = max(0, min(100, (int) ($scores['fluency'] ?? 0)));
		$confidence = max(0, min(100, (int) ($scores['confidence'] ?? 0)));
		$structure = max(0, min(100, (int) ($scores['structure'] ?? 0)));
		$intonation = max(0, min(100, (int) ($scores['intonation'] ?? 0)));
		$finalScore = (int) round(($articulation + $fluency + $confidence + $structure + $intonation) / 5);

		$sql = "INSERT INTO mentor_reviews (
			submission_id, mentor_id, articulation_score, fluency_score, confidence_score,
			structure_score, intonation_score, final_score, strengths, improvements, feedback
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		ON DUPLICATE KEY UPDATE
			mentor_id = VALUES(mentor_id),
			articulation_score = VALUES(articulation_score),
			fluency_score = VALUES(fluency_score),
			confidence_score = VALUES(confidence_score),
			structure_score = VALUES(structure_score),
			intonation_score = VALUES(intonation_score),
			final_score = VALUES(final_score),
			strengths = VALUES(strengths),
			improvements = VALUES(improvements),
			feedback = VALUES(feedback)";
		$stmt = mysqli_prepare($this->koneksi, $sql);
		if (!$stmt) return false;
		mysqli_stmt_bind_param(
			$stmt,
			"iiiiiiiisss",
			$submissionId,
			$mentorId,
			$articulation,
			$fluency,
			$confidence,
			$structure,
			$intonation,
			$finalScore,
			$strengths,
			$improvements,
			$feedback
		);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		if ($saved) {
			$update = mysqli_prepare($this->koneksi, "UPDATE mentor_submissions SET mentor_id = ?, status = 'reviewed', reviewed_at = NOW() WHERE id = ?");
			if ($update) {
				mysqli_stmt_bind_param($update, "ii", $mentorId, $submissionId);
				$saved = mysqli_stmt_execute($update);
				mysqli_stmt_close($update);
			}
		}

		return $saved;
	}

	public function submitToMentor($practiceHistoryId, $userId, $featureType = 'voice'){
		$validTypes = ['voice', 'challenge', 'camera'];
		if (!in_array($featureType, $validTypes, true)) return false;

		$historyTables = [
			'voice' => 'practice_history',
			'challenge' => 'speaking_challenge_history',
			'camera' => 'camera_practice_history'
		];
		$ownerCheck = mysqli_prepare($this->koneksi, "SELECT 1 FROM {$historyTables[$featureType]} WHERE id = ? AND user_id = ? LIMIT 1");
		if (!$ownerCheck) return false;
		mysqli_stmt_bind_param($ownerCheck, "is", $practiceHistoryId, $userId);
		mysqli_stmt_execute($ownerCheck);
		$ownedPractice = mysqli_stmt_get_result($ownerCheck);
		$isOwned = $ownedPractice && mysqli_num_rows($ownedPractice) === 1;
		mysqli_stmt_close($ownerCheck);
		if (!$isOwned) return false;

		// Find mentor with matching specialty
		$mentor = $this->getMentorBySpecialty($featureType);
		$mentorId = $mentor ? (int) $mentor['id'] : null;

		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO mentor_submissions (practice_history_id, user_id, mentor_id, feature_type) VALUES (?, ?, ?, ?)");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, "isis", $practiceHistoryId, $userId, $mentorId, $featureType);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $saved;
	}

	public function getMentorBySpecialty($specialty){
		$specEsc = mysqli_real_escape_string($this->koneksi, $specialty);
		$res = mysqli_query($this->koneksi, "SELECT id, name, username, specialty FROM mentor_accounts WHERE specialty = '$specEsc' LIMIT 1");
		return $res && mysqli_num_rows($res) > 0 ? mysqli_fetch_assoc($res) : false;
	}

	public function getMentorInfoForFeatures(){
		$mentors = ['voice' => null, 'challenge' => null, 'camera' => null];
		$res = mysqli_query($this->koneksi, "SELECT id, name, username, specialty FROM mentor_accounts WHERE specialty IN ('voice','challenge','camera')");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$mentors[$row['specialty']] = $row;
			}
		}
		return $mentors;
	}

	public function getUserReviewResults($userId, $limit = 20){
		$items = [];
		$limit = max(1, (int) $limit);
		$stmt = mysqli_prepare($this->koneksi, "SELECT
			ms.id AS submission_id,
			ms.status,
			ms.feature_type,
			ms.submitted_at,
			ms.reviewed_at,
			COALESCE(ph.topic, ch.challenge_type, cp.topic) AS topic,
			ph.script_title,
			COALESCE(ph.category, cp.simulation_mode) AS category,
			ph.level_name,
			COALESCE(ph.duration_seconds, ch.actual_seconds, cp.duration_seconds) AS duration_seconds,
			COALESCE(ph.audio_path, ch.audio_path) AS audio_path,
			cp.video_path,
			ma.name AS mentor_name,
			ma.specialty AS mentor_specialty,
			mr.articulation_score,
			mr.fluency_score,
			mr.confidence_score,
			mr.structure_score,
			mr.intonation_score,
			mr.final_score,
			mr.strengths,
			mr.improvements,
			mr.feedback
			FROM mentor_submissions ms
			LEFT JOIN practice_history ph ON ph.id = ms.practice_history_id AND ms.feature_type = 'voice'
			LEFT JOIN speaking_challenge_history ch ON ch.id = ms.practice_history_id AND ms.feature_type = 'challenge'
			LEFT JOIN camera_practice_history cp ON cp.id = ms.practice_history_id AND ms.feature_type = 'camera'
			LEFT JOIN mentor_accounts ma ON ma.id = ms.mentor_id
			LEFT JOIN mentor_reviews mr ON mr.submission_id = ms.id
			WHERE ms.user_id = ?
			ORDER BY ms.submitted_at DESC
			LIMIT $limit");
		if (!$stmt) return $items;
		mysqli_stmt_bind_param($stmt, "s", $userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)) {
			$items[] = $row;
		}
		mysqli_stmt_close($stmt);
		return $items;
	}

	public function getUserReviewStats($userId){
		$stats = ['total' => 0, 'pending' => 0, 'reviewed' => 0, 'average_score' => 0];
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$res = mysqli_query($this->koneksi, "SELECT
			COUNT(*) AS total,
			SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_total,
			SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) AS reviewed_total
			FROM mentor_submissions WHERE user_id = '$userIdEsc'");
		if ($res && $row = mysqli_fetch_assoc($res)) {
			$stats['total'] = (int) ($row['total'] ?? 0);
			$stats['pending'] = (int) ($row['pending_total'] ?? 0);
			$stats['reviewed'] = (int) ($row['reviewed_total'] ?? 0);
		}
		$scoreRes = mysqli_query($this->koneksi, "SELECT AVG(mr.final_score) AS avg_score
			FROM mentor_reviews mr
			JOIN mentor_submissions ms ON ms.id = mr.submission_id
			WHERE ms.user_id = '$userIdEsc'");
		if ($scoreRes && $row = mysqli_fetch_assoc($scoreRes)) {
			$stats['average_score'] = (int) round((float) ($row['avg_score'] ?? 0));
		}
		return $stats;
	}

	public function getAvailableSpecialties(){
		$taken = [];
		$res = mysqli_query($this->koneksi, "SELECT specialty FROM mentor_accounts");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$taken[] = $row['specialty'];
			}
		}
		$all = ['voice', 'challenge', 'camera'];
		return array_values(array_diff($all, $taken));
	}

	public function getLastInsertedPracticeId(){
		return (int) mysqli_insert_id($this->koneksi);
	}

	public function ensurePracticeHistoryTable(){
		$sql = "CREATE TABLE IF NOT EXISTS practice_history (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(6) NOT NULL,
			topic VARCHAR(255) NOT NULL,
			script_title VARCHAR(255) NULL DEFAULT NULL,
			category VARCHAR(60) NULL DEFAULT NULL,
			level_name VARCHAR(30) NULL DEFAULT NULL,
			duration_seconds INT UNSIGNED NOT NULL,
			audio_path VARCHAR(255) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_practice_user (user_id),
			CONSTRAINT fk_practice_user FOREIGN KEY (user_id) REFERENCES users(Id_User)
			ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
		$this->ensurePracticeHistoryMetadataColumns();
	}

	private function ensurePracticeHistoryMetadataColumns(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'practice_history'");
		if (!$table || mysqli_num_rows($table) === 0) {
			return;
		}

		$columns = [
			'script_title' => "ALTER TABLE practice_history ADD COLUMN script_title VARCHAR(255) NULL DEFAULT NULL AFTER topic",
			'category' => "ALTER TABLE practice_history ADD COLUMN category VARCHAR(60) NULL DEFAULT NULL AFTER script_title",
			'level_name' => "ALTER TABLE practice_history ADD COLUMN level_name VARCHAR(30) NULL DEFAULT NULL AFTER category"
		];

		foreach ($columns as $column => $alterSql) {
			$check = mysqli_query($this->koneksi, "SHOW COLUMNS FROM practice_history LIKE '$column'");
			if ($check && mysqli_num_rows($check) === 0) {
				mysqli_query($this->koneksi, $alterSql);
			}
		}
	}

	public function ensureChallengeHistoryTable(){
		$sql = "CREATE TABLE IF NOT EXISTS speaking_challenge_history (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(6) NOT NULL,
			challenge_type VARCHAR(60) NOT NULL,
			level_name VARCHAR(30) NOT NULL,
			question_count INT UNSIGNED NOT NULL DEFAULT 1,
			prompt TEXT NOT NULL,
			prep_seconds INT UNSIGNED NOT NULL,
			speak_seconds INT UNSIGNED NOT NULL,
			actual_seconds INT UNSIGNED NOT NULL,
			score INT UNSIGNED NOT NULL,
			completed TINYINT(1) NOT NULL DEFAULT 1,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_challenge_user (user_id),
			CONSTRAINT fk_challenge_user FOREIGN KEY (user_id) REFERENCES users(Id_User)
			ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
		$this->ensureChallengeHistoryMetadataColumns();
	}

	private function ensureChallengeHistoryMetadataColumns(){
		$table = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'speaking_challenge_history'");
		if (!$table || mysqli_num_rows($table) === 0) {
			return;
		}

		$check = mysqli_query($this->koneksi, "SHOW COLUMNS FROM speaking_challenge_history LIKE 'question_count'");
		if ($check && mysqli_num_rows($check) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE speaking_challenge_history ADD COLUMN question_count INT UNSIGNED NOT NULL DEFAULT 1 AFTER level_name");
		}

		$checkAudio = mysqli_query($this->koneksi, "SHOW COLUMNS FROM speaking_challenge_history LIKE 'audio_path'");
		if ($checkAudio && mysqli_num_rows($checkAudio) === 0) {
			mysqli_query($this->koneksi, "ALTER TABLE speaking_challenge_history ADD COLUMN audio_path VARCHAR(255) NULL DEFAULT NULL AFTER completed");
		}
	}

	public function ensureAiFeedbackTable(){
		$sql = "CREATE TABLE IF NOT EXISTS ai_feedback_history (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(6) NOT NULL,
			source_type VARCHAR(40) NOT NULL,
			duration_seconds INT UNSIGNED NOT NULL,
			clarity_score INT UNSIGNED NOT NULL,
			fluency_score INT UNSIGNED NOT NULL,
			confidence_score INT UNSIGNED NOT NULL,
			consistency_score INT UNSIGNED NOT NULL,
			filler_count INT UNSIGNED NOT NULL,
			speaking_speed INT UNSIGNED NOT NULL,
			feedback TEXT NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_ai_feedback_user (user_id),
			CONSTRAINT fk_ai_feedback_user FOREIGN KEY (user_id) REFERENCES users(Id_User)
			ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
	}

	public function getPracticeHistory($userId, $limit = 10){
		$history = [];
		$limit = (int) $limit;
		$stmt = mysqli_prepare($this->koneksi, "SELECT topic, COALESCE(script_title, topic) AS script_title, COALESCE(category, 'Latihan Suara') AS category, COALESCE(level_name, '') AS level_name, duration_seconds, audio_path, created_at FROM practice_history WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
		if (!$stmt) return $history;
		mysqli_stmt_bind_param($stmt, "s", $userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)){
			$history[] = $row;
		}
		mysqli_stmt_close($stmt);
		return $history;
	}

	public function getChallengeHistory($userId, $limit = 10){
		$history = [];
		$limit = (int) $limit;
		$stmt = mysqli_prepare($this->koneksi, "SELECT challenge_type, level_name, question_count, prompt, prep_seconds, speak_seconds, actual_seconds, score, completed, created_at FROM speaking_challenge_history WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
		if (!$stmt) return $history;
		mysqli_stmt_bind_param($stmt, "s", $userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)){
			$history[] = $row;
		}
		mysqli_stmt_close($stmt);
		return $history;
	}

	public function getAiFeedbackHistory($userId, $limit = 10){
		$history = [];
		$limit = (int) $limit;
		$stmt = mysqli_prepare($this->koneksi, "SELECT source_type, duration_seconds, clarity_score, fluency_score, confidence_score, consistency_score, filler_count, speaking_speed, feedback, created_at FROM ai_feedback_history WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
		if (!$stmt) return $history;
		mysqli_stmt_bind_param($stmt, "s", $userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)){
			$history[] = $row;
		}
		mysqli_stmt_close($stmt);
		return $history;
	}

	public function savePracticeHistory($userId, $topic, $durationSeconds, $audioPath, $scriptTitle = '', $category = '', $levelName = ''){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO practice_history (user_id, topic, script_title, category, level_name, duration_seconds, audio_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		$durationSeconds = (int) $durationSeconds;
		$scriptTitle = trim($scriptTitle) !== '' ? trim($scriptTitle) : $topic;
		$category = trim($category);
		$levelName = trim($levelName);
		mysqli_stmt_bind_param($stmt, "sssssis", $userId, $topic, $scriptTitle, $category, $levelName, $durationSeconds, $audioPath);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $saved;
	}

	public function saveChallengeHistory($userId, $challengeType, $levelName, $prompt, $prepSeconds, $speakSeconds, $actualSeconds, $score, $completed, $questionCount = 1, $audioPath = null){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO speaking_challenge_history (user_id, challenge_type, level_name, question_count, prompt, prep_seconds, speak_seconds, actual_seconds, score, completed, audio_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		$questionCount = max(1, (int) $questionCount);
		$prepSeconds = (int) $prepSeconds;
		$speakSeconds = (int) $speakSeconds;
		$actualSeconds = (int) $actualSeconds;
		$score = (int) $score;
		$completed = (int) $completed;
		mysqli_stmt_bind_param($stmt, "sssisiiiiss", $userId, $challengeType, $levelName, $questionCount, $prompt, $prepSeconds, $speakSeconds, $actualSeconds, $score, $completed, $audioPath);
		$saved = mysqli_stmt_execute($stmt);
		$insertId = $saved ? mysqli_insert_id($this->koneksi) : false;
		mysqli_stmt_close($stmt);
		return $insertId;
	}

	public function saveAiFeedbackHistory($userId, $sourceType, $durationSeconds, $clarity, $fluency, $confidence, $consistency, $fillerCount, $speakingSpeed, $feedback){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO ai_feedback_history (user_id, source_type, duration_seconds, clarity_score, fluency_score, confidence_score, consistency_score, filler_count, speaking_speed, feedback) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		$durationSeconds = (int) $durationSeconds;
		$clarity = (int) $clarity;
		$fluency = (int) $fluency;
		$confidence = (int) $confidence;
		$consistency = (int) $consistency;
		$fillerCount = (int) $fillerCount;
		$speakingSpeed = (int) $speakingSpeed;
		mysqli_stmt_bind_param($stmt, "ssiiiiiiis", $userId, $sourceType, $durationSeconds, $clarity, $fluency, $confidence, $consistency, $fillerCount, $speakingSpeed, $feedback);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $saved;
	}

	public function handleSavePractice($currentUser){

	if (!$currentUser) {
		http_response_code(401);
		return [
			'status' => false,
			'message' => 'Silakan login untuk menyimpan riwayat latihan.'
		];
	}

	$topic = trim($_POST['topic'] ?? '');
	$scriptTitle = trim($_POST['script_title'] ?? $topic);
	$category = trim($_POST['category'] ?? '');
	$levelName = trim($_POST['level_name'] ?? '');
	$duration = (int) ($_POST['duration'] ?? 0);
	$maxAudioBytes = 25 * 1024 * 1024;

	if ($topic === '' || $duration <= 0 || $duration > 3600 || empty($_FILES['audio']['tmp_name'])) {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'Data latihan belum lengkap.'
		];
	}

	if ($_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'File audio gagal diterima.'
		];
	}

	if (!is_uploaded_file($_FILES['audio']['tmp_name']) || (int) $_FILES['audio']['size'] <= 0 || (int) $_FILES['audio']['size'] > $maxAudioBytes) {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'Ukuran file audio tidak valid. Maksimal 25 MB.'
		];
	}

	$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
	$mimeType = $fileInfo ? finfo_file($fileInfo, $_FILES['audio']['tmp_name']) : false;
	if ($fileInfo) finfo_close($fileInfo);
	$allowedAudioTypes = ['audio/webm', 'video/webm'];
	if (!in_array($mimeType, $allowedAudioTypes, true)) {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'Format audio tidak didukung. Gunakan rekaman WebM dari aplikasi.'
		];
	}

	$uploadDir = __DIR__ . '/uploads/practice_audio';

	if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
		http_response_code(500);
		return [
			'status' => false,
			'message' => 'Folder penyimpanan audio tidak bisa dibuat.'
		];
	}

	$fileName = $currentUser['Id_User'] . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.webm';

	$targetPath = $uploadDir . '/' . $fileName;
	$relativePath = 'uploads/practice_audio/' . $fileName;

	if (!move_uploaded_file($_FILES['audio']['tmp_name'], $targetPath)) {
		http_response_code(500);
		return [
			'status' => false,
			'message' => 'Gagal menyimpan file audio.'
		];
	}

	$saved = $this->savePracticeHistory(
		$currentUser['Id_User'],
		$topic,
		$duration,
		$relativePath,
		$scriptTitle,
		$category,
		$levelName
	);

	if (!$saved) {
		if (is_file($targetPath)) unlink($targetPath);
		http_response_code(500);
		return [
			'status' => false,
			'message' => 'Gagal menyimpan riwayat latihan.'
		];
	}

	$practiceId = $this->getLastInsertedPracticeId();

	return [
		'status' => true,
		'message' => 'Riwayat latihan berhasil disimpan.',
		'practice_history_id' => $practiceId,
		'item' => [
			'topic' => $topic,
			'script_title' => $scriptTitle,
			'category' => $category,
			'level_name' => $levelName,
			'duration_seconds' => $duration,
			'audio_path' => $relativePath,
			'created_at' => date('Y-m-d H:i:s')
		]
	];
}

public function handleSubmitToMentor($currentUser){
	if (!$currentUser) {
		http_response_code(401);
		return ['status' => false, 'message' => 'Silakan login terlebih dahulu.'];
	}

	$practiceHistoryId = (int) ($_POST['practice_history_id'] ?? 0);
	$featureType = trim($_POST['feature_type'] ?? 'voice');

	if ($practiceHistoryId <= 0) {
		http_response_code(400);
		return ['status' => false, 'message' => 'ID latihan tidak valid.'];
	}

	$submitted = $this->submitToMentor($practiceHistoryId, $currentUser['Id_User'], $featureType);

	if (!$submitted) {
		http_response_code(500);
		return ['status' => false, 'message' => 'Gagal mengirim latihan ke mentor. Mungkin sudah pernah dikirim.'];
	}

	$mentor = $this->getMentorBySpecialty($featureType);
	$mentorName = $mentor ? $mentor['name'] : 'Mentor';

	return [
		'status' => true,
		'message' => "Latihan berhasil dikirim ke $mentorName untuk penilaian."
	];
}

public function handleSaveChallenge($currentUser){

	if (!$currentUser) {
		http_response_code(401);
		return [
			'status' => false,
			'message' => 'Silakan login untuk menyimpan riwayat challenge.'
		];
	}

	$challengeType = trim($_POST['challenge_type'] ?? '');
	$levelName = trim($_POST['level_name'] ?? '');
	$questionCount = max(1, (int) ($_POST['question_count'] ?? 1));
	$prompt = trim($_POST['prompt'] ?? '');
	$prepSeconds = (int) ($_POST['prep_seconds'] ?? 0);
	$speakSeconds = (int) ($_POST['speak_seconds'] ?? 0);
	$actualSeconds = (int) ($_POST['actual_seconds'] ?? 0);
	$score = max(0, min(100, (int) ($_POST['score'] ?? 0)));
	$completed = (int) ($_POST['completed'] ?? 1);

	if ($challengeType === '' || $levelName === '' || $prompt === '') {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'Data challenge belum lengkap.'
		];
	}

	// Handle audio upload for challenge
	$audioPath = null;
	if (!empty($_FILES['audio']['tmp_name']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
		$uploadDir = __DIR__ . '/uploads/challenge_audio';
		if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
		$fileName = $currentUser['Id_User'] . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.webm';
		$targetPath = $uploadDir . '/' . $fileName;
		if (move_uploaded_file($_FILES['audio']['tmp_name'], $targetPath)) {
			$audioPath = 'uploads/challenge_audio/' . $fileName;
		}
	}

	$saved = $this->saveChallengeHistory(
		$currentUser['Id_User'],
		$challengeType,
		$levelName,
		$prompt,
		$prepSeconds,
		$speakSeconds,
		$actualSeconds,
		$score,
		$completed,
		$questionCount,
		$audioPath
	);

	if (!$saved) {
		http_response_code(500);
		return [
			'status' => false,
			'message' => 'Gagal menyimpan riwayat challenge.'
		];
	}

	return [
		'status' => true,
		'message' => 'Riwayat challenge berhasil disimpan.',
		'practice_history_id' => $saved,
		'item' => [
			'challenge_type' => $challengeType,
			'level_name' => $levelName,
			'question_count' => $questionCount,
			'prompt' => $prompt,
			'prep_seconds' => $prepSeconds,
			'speak_seconds' => $speakSeconds,
			'actual_seconds' => $actualSeconds,
			'score' => $score,
			'completed' => $completed,
			'created_at' => date('Y-m-d H:i:s')
		]
	];
}

public function handleSaveAiFeedback($currentUser){

	if (!$currentUser) {
		http_response_code(401);
		return [
			'status' => false,
			'message' => 'Silakan login untuk menyimpan AI feedback.'
		];
	}

	$sourceType = trim($_POST['source_type'] ?? 'Voice Practice');
	$duration = max(0, (int) ($_POST['duration_seconds'] ?? 0));
	$clarity = max(0, min(100, (int) ($_POST['clarity_score'] ?? 0)));
	$fluency = max(0, min(100, (int) ($_POST['fluency_score'] ?? 0)));
	$confidence = max(0, min(100, (int) ($_POST['confidence_score'] ?? 0)));
	$consistency = max(0, min(100, (int) ($_POST['consistency_score'] ?? 0)));
	$fillerCount = max(0, (int) ($_POST['filler_count'] ?? 0));
	$speakingSpeed = max(0, (int) ($_POST['speaking_speed'] ?? 0));
	$feedback = trim($_POST['feedback'] ?? '');

	if ($duration <= 0 || $feedback === '') {
		http_response_code(400);
		return [
			'status' => false,
			'message' => 'Data feedback belum lengkap.'
		];
	}

	$saved = $this->saveAiFeedbackHistory(
		$currentUser['Id_User'],
		$sourceType,
		$duration,
		$clarity,
		$fluency,
		$confidence,
		$consistency,
		$fillerCount,
		$speakingSpeed,
		$feedback
	);

	if (!$saved) {
		http_response_code(500);
		return [
			'status' => false,
			'message' => 'Gagal menyimpan AI feedback.'
		];
	}

	return [
		'status' => true,
		'message' => 'AI feedback berhasil disimpan.'
	];
}

	public function ensureEbookTable(){
		$tableExists = false;
		$tableCheck = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'ebooks'");
		if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
			$tableExists = true;
		}

		$create = "CREATE TABLE IF NOT EXISTS ebooks (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			title VARCHAR(180) NOT NULL,
			author VARCHAR(120) NOT NULL,
			pages INT UNSIGNED NOT NULL DEFAULT 0,
			thumbnail_path VARCHAR(255) NOT NULL,
			pdf_path VARCHAR(255) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_ebook_title (title)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $create);

		if ($tableExists) return;

		$countRes = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM ebooks");
		if (!$countRes) return;

		$count = mysqli_fetch_assoc($countRes);
		if ((int) ($count['total'] ?? 0) > 0) return;

		$defaults = [
			['3 Teknik Mahir Berbicara Di Depan Publik', 'Hebbie Agus Kurnia', 32, 'assets/ebook/ebook1.png', 'assets/ebook/ebook1.pdf'],
			['Public Speaking Untuk Pemula', 'Rinna Raflina, S.Sos., M.I.Kom', 88, 'assets/ebook/ebook2.png', 'assets/ebook/ebook2.pdf'],
			['My Public Speaking', 'Hilbram Dunar', 180, 'assets/ebook/ebook3.png', 'assets/ebook/ebook3.pdf'],
			['Dasar Public Speaking', 'Dr. Mohamed Sudi, S.E., M.Si.', 116, 'assets/ebook/ebook4.png', 'assets/ebook/ebook4.pdf'],
		];

		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO ebooks (title, author, pages, thumbnail_path, pdf_path) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt) return;

		foreach ($defaults as $ebook) {
			$title = $ebook[0];
			$author = $ebook[1];
			$pages = $ebook[2];
			$thumbnailPath = $ebook[3];
			$pdfPath = $ebook[4];
			mysqli_stmt_bind_param($stmt, "ssiss", $title, $author, $pages, $thumbnailPath, $pdfPath);
			mysqli_stmt_execute($stmt);
		}

		mysqli_stmt_close($stmt);
	}

	public function ensureEbookHistoryTable(){
		$sql = "CREATE TABLE IF NOT EXISTS ebook_activity (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(6) NOT NULL,
			ebook_id INT UNSIGNED NOT NULL,
			ebook_title VARCHAR(255) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_ebook_activity_user (user_id),
			CONSTRAINT fk_ebook_activity_user FOREIGN KEY (user_id) REFERENCES users(Id_User) ON DELETE CASCADE,
			CONSTRAINT fk_ebook_activity_ebook FOREIGN KEY (ebook_id) REFERENCES ebooks(id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
	}

	public function getEbookById($ebookId){
		$ebookIdInt = (int) $ebookId;
		$sql = "SELECT id, title FROM ebooks WHERE id = $ebookIdInt LIMIT 1";
		$res = mysqli_query($this->koneksi, $sql);
		if ($res && mysqli_num_rows($res) > 0){
			return mysqli_fetch_assoc($res);
		}
		return false;
	}

	public function saveEbookActivity($userId, $ebookId, $ebookTitle){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$ebookIdInt = (int) $ebookId;
		$ebookTitleEsc = mysqli_real_escape_string($this->koneksi, $ebookTitle);
		$sql = "INSERT INTO ebook_activity (user_id, ebook_id, ebook_title) VALUES ('$userIdEsc', $ebookIdInt, '$ebookTitleEsc')";
		return mysqli_query($this->koneksi, $sql);
	}

	public function getEbookActivityHistory($userId, $limit = 10){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$sql = "SELECT ebook_id, ebook_title, created_at FROM ebook_activity WHERE user_id = '$userIdEsc' ORDER BY created_at DESC LIMIT $limit";
		$res = mysqli_query($this->koneksi, $sql);
		$history = [];
		if ($res){
			while ($row = mysqli_fetch_assoc($res)){
				$history[] = $row;
			}
		}
		return $history;
	}

	public function getEbooks($search = ''){
		$search = trim($search);
		$ebooks = [];

		if ($search !== '') {
			$like = '%' . $search . '%';
			$stmt = mysqli_prepare($this->koneksi, "SELECT id, title, author, pages, thumbnail_path, pdf_path, created_at FROM ebooks WHERE title LIKE ? ORDER BY created_at DESC, id DESC");
			if (!$stmt) return $ebooks;
			mysqli_stmt_bind_param($stmt, "s", $like);
			mysqli_stmt_execute($stmt);
			$res = mysqli_stmt_get_result($stmt);
		} else {
			$res = mysqli_query($this->koneksi, "SELECT id, title, author, pages, thumbnail_path, pdf_path, created_at FROM ebooks ORDER BY created_at DESC, id DESC");
		}

		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$ebooks[] = $row;
			}
		}

		if (isset($stmt) && $stmt) {
			mysqli_stmt_close($stmt);
		}

		return $ebooks;
	}

	public function createEbook($title, $author, $pages, $thumbnailPath, $pdfPath){
		$title = trim($title);
		$author = trim($author);
		$pages = max(0, (int) $pages);

		if ($title === '' || $author === '' || $thumbnailPath === '' || $pdfPath === '') {
			return false;
		}

		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO ebooks (title, author, pages, thumbnail_path, pdf_path) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt) return false;

		mysqli_stmt_bind_param($stmt, "ssiss", $title, $author, $pages, $thumbnailPath, $pdfPath);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		return $saved;
	}

	public function deleteEbook($ebookId){
		$ebookId = (int) $ebookId;
		if ($ebookId <= 0) return false;

		$stmt = mysqli_prepare($this->koneksi, "DELETE FROM ebooks WHERE id = ? LIMIT 1");
		if (!$stmt) return false;

		mysqli_stmt_bind_param($stmt, "i", $ebookId);
		$deleted = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		return $deleted;
	}

	public function getCompletedVideoIds($userId, $materialId){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$materialIdEsc = mysqli_real_escape_string($this->koneksi, $materialId);
		$sql = "SELECT p.video_id FROM material_video_progress p
				JOIN material_videos v ON p.video_id = v.id
				WHERE p.user_id = '$userIdEsc' AND v.material_id = '$materialIdEsc'";
		$res = mysqli_query($this->koneksi, $sql);
		$ids = [];
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$ids[] = (int)$row['video_id'];
			}
		}
		return $ids;
	}

	public function getMaterialProgressCount($userId, $materialId){
		$completedIds = $this->getCompletedVideoIds($userId, $materialId);
		return count($completedIds);
	}

	public function getMaterialProgress($userId, $materialId){
		$videos = $this->getMaterialVideos($materialId);
		$total = count($videos);
		if ($total <= 0) return 0;

		$completed = $this->getMaterialProgressCount($userId, $materialId);
		$completed = max(0, min($completed, $total));

		return (int) round(($completed / $total) * 100);
	}

	public function getOverallMaterialProgress($userId){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);

		$totalVideos = 0;
		$totalRes = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM material_videos");
		if ($totalRes) {
			$row = mysqli_fetch_assoc($totalRes);
			$totalVideos = (int) ($row['total'] ?? 0);
		}

		$completedVideos = 0;
		$completedRes = mysqli_query($this->koneksi, "SELECT COUNT(DISTINCT p.video_id) AS total
			FROM material_video_progress p
			JOIN material_videos v ON p.video_id = v.id
			WHERE p.user_id = '$userIdEsc'");
		if ($completedRes) {
			$row = mysqli_fetch_assoc($completedRes);
			$completedVideos = (int) ($row['total'] ?? 0);
		}

		$totalMaterials = 0;
		$completedMaterials = 0;
		$materials = $this->getMaterials();
		foreach ($materials as $material) {
			$videos = $this->getMaterialVideos($material['id']);
			$total = count($videos);
			if ($total <= 0) continue;

			$totalMaterials++;
			$completed = $this->getMaterialProgressCount($userId, $material['id']);
			if ($completed >= $total) {
				$completedMaterials++;
			}
		}

		$completedVideos = max(0, min($completedVideos, $totalVideos));
		$percent = $totalVideos > 0 ? (int) round(($completedVideos / $totalVideos) * 100) : 0;

		return [
			'percent' => $percent,
			'completed_videos' => $completedVideos,
			'total_videos' => $totalVideos,
			'completed_materials' => $completedMaterials,
			'total_materials' => $totalMaterials
		];
	}

	public function saveVideoProgress($userId, $videoId){
		$userIdEsc = mysqli_real_escape_string($this->koneksi, $userId);
		$videoIdInt = (int) $videoId;
		$sql = "INSERT IGNORE INTO material_video_progress (user_id, video_id) VALUES ('$userIdEsc', $videoIdInt)";
		return mysqli_query($this->koneksi, $sql);
	}

	public function ensureMaterialsTables(){
		$sql = "CREATE TABLE IF NOT EXISTS materials (
			id VARCHAR(50) NOT NULL,
			title VARCHAR(100) NOT NULL,
			description VARCHAR(255) NOT NULL,
			category VARCHAR(50) NOT NULL,
			time_minutes INT NOT NULL DEFAULT 10,
			icon_file VARCHAR(50) NOT NULL,
			color_class VARCHAR(30) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS material_videos (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			material_id VARCHAR(50) NOT NULL,
			title VARCHAR(255) NOT NULL,
			video_url VARCHAR(255) NOT NULL,
			script TEXT NOT NULL,
			order_index INT NOT NULL DEFAULT 0,
			PRIMARY KEY (id),
			KEY idx_material (material_id),
			CONSTRAINT fk_mat_vid FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);

		$this->seedMaterialsTable();

		$sql = "CREATE TABLE IF NOT EXISTS material_video_progress (
			user_id VARCHAR(6) NOT NULL,
			video_id INT UNSIGNED NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (user_id, video_id),
			CONSTRAINT fk_mvp_user FOREIGN KEY (user_id) REFERENCES users(Id_User) ON DELETE CASCADE,
			CONSTRAINT fk_mvp_video FOREIGN KEY (video_id) REFERENCES material_videos(id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);

		$this->migrateOldProgress();
	}

	private function migrateOldProgress() {
		$check = mysqli_query($this->koneksi, "SELECT COUNT(*) as cnt FROM material_video_progress");
		if ($check) {
			$row = mysqli_fetch_assoc($check);
			if ($row['cnt'] > 0) return;
		}

		$res = mysqli_query($this->koneksi, "SELECT * FROM material_progress");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$uid = $row['user_id'];
				$mid = $row['material_id'];
				$prog = (int)$row['progress'];
				
				$videos = $this->getMaterialVideos($mid);
				for ($i = 0; $i <= $prog; $i++) {
					if (isset($videos[$i])) {
						$this->saveVideoProgress($uid, $videos[$i]['id']);
					}
				}
			}
		}
	}

	private function seedMaterialsTable(){
		$res = mysqli_query($this->koneksi, "SELECT COUNT(*) as cnt FROM materials");
		$row = mysqli_fetch_assoc($res);
		if ($row['cnt'] > 0) return;

		$defaults = [
			['vokal', 'Vokal yang Jelas', 'Belajar mengucapkan kata dengan jelas dan tegas', 'vokal', 15, 'icon/mic.svg', 'vokal'],
			['postur_tubuh', 'Postur Tubuh', 'Cara berdiri dan bergerak yang percaya diri', 'gerak tubuh', 20, 'icon/badan.svg', 'gerak-tubuh'],
			['kontak_mata', 'Kontak Mata', 'Teknik menatap audiens dengan nyaman', 'gerak tubuh', 10, 'icon/mata.svg', 'kontak-mata'],
			['intonasi_suara', 'Intonasi Suara', 'Mengatur naik turunnya suara saat berbicara', 'vokal', 10, 'icon/ear.svg', 'intonasi'],
			['mengatasi_grogi', 'Mengatasi Grogi', 'Tips menghilangkan rasa gugup di depan umum', 'lainnya', 25, 'icon/halo.svg', 'mengatasi-grogi'],
			['gestur_tangan', 'Gestur Tangan', 'Menggunakan gerakan tangan untuk memperkuat pesan', 'gerak tubuh', 10, 'icon/emot.svg', 'gestur-tangan'],
			['penyusunan_materi', 'Penyusunan Materi', 'Penyampaian isi yang sistematis', 'lainnya', 15, 'icon/book.svg', 'penyusunan-materi'],
			['media_presentasi', 'Media Presentasi', 'Tips menggunakan microphone dan panggung', 'lainnya', 15, 'icon/media.svg', 'media-presentasi']
		];

		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO materials (id, title, description, category, time_minutes, icon_file, color_class) VALUES (?, ?, ?, ?, ?, ?, ?)");
		if ($stmt) {
			foreach ($defaults as $m) {
				mysqli_stmt_bind_param($stmt, "ssssiss", $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6]);
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}

		$videoDefaults = [
			['vokal', '1. Artikulasi Dasar dan Resonansi', 'https://youtu.be/-2NnNomW68k?si=m_7WFa0C5o3_c5Yz', 'Video penjelasan tentang artikulasi.', 1],
			['vokal', '2. Teknik Pernapasan', 'https://youtu.be/YhmbAxzxamo?si=C1EWqw5oIzZf-GjR', 'Pernapasan diafragma membantu suara menjadi lebih bertenaga dan stabil.', 2],
			['vokal', '3. 8 Teknik Vokal', 'https://youtu.be/VGFVkRpv-SE?si=kDH2-PPod_9r27hT', 'Delapan teknik vokal dalam public speaking.', 3],
			['postur_tubuh', '1. Pentingnya Postur Tubuh', 'https://youtu.be/Wb2iQ1pTIf4?si=R95-rLST_yO49H8W', 'Postur tubuh menentukan kepercayaan diri.', 1]
		];

		$stmtV = mysqli_prepare($this->koneksi, "INSERT INTO material_videos (material_id, title, video_url, script, order_index) VALUES (?, ?, ?, ?, ?)");
		if ($stmtV) {
			foreach ($videoDefaults as $v) {
				mysqli_stmt_bind_param($stmtV, "ssssi", $v[0], $v[1], $v[2], $v[3], $v[4]);
				mysqli_stmt_execute($stmtV);
			}
			mysqli_stmt_close($stmtV);
		}
	}

	public function getMaterials($category = null){
		$sql = "SELECT * FROM materials";
		if ($category) {
			$catEsc = mysqli_real_escape_string($this->koneksi, $category);
			$sql .= " WHERE category = '$catEsc'";
		}
		$sql .= " ORDER BY CASE id 
			WHEN 'vokal' THEN 1
			WHEN 'postur_tubuh' THEN 2
			WHEN 'kontak_mata' THEN 3
			WHEN 'intonasi_suara' THEN 4
			WHEN 'mengatasi_grogi' THEN 5
			WHEN 'gestur_tangan' THEN 6
			WHEN 'penyusunan_materi' THEN 7
			WHEN 'media_presentasi' THEN 8
			ELSE 99 END ASC, created_at DESC";
		
		$res = mysqli_query($this->koneksi, $sql);
		$materials = [];
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$materials[] = $row;
			}
		}
		return $materials;
	}

	public function getMaterialById($id){
		$idEsc = mysqli_real_escape_string($this->koneksi, $id);
		$res = mysqli_query($this->koneksi, "SELECT * FROM materials WHERE id = '$idEsc' LIMIT 1");
		return $res ? mysqli_fetch_assoc($res) : false;
	}

	public function createMaterial($id, $title, $description, $category, $time_minutes, $icon_file, $color_class){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO materials (id, title, description, category, time_minutes, icon_file, color_class) VALUES (?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, "ssssiss", $id, $title, $description, $category, $time_minutes, $icon_file, $color_class);
		$res = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $res;
	}

	public function updateMaterial($id, $title, $description, $category, $time_minutes, $icon_file, $color_class){
		$stmt = mysqli_prepare($this->koneksi, "UPDATE materials SET title=?, description=?, category=?, time_minutes=?, icon_file=?, color_class=? WHERE id=?");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, "sssisss", $title, $description, $category, $time_minutes, $icon_file, $color_class, $id);
		$res = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $res;
	}

	public function deleteMaterial($id){
		$idEsc = mysqli_real_escape_string($this->koneksi, $id);
		return mysqli_query($this->koneksi, "DELETE FROM materials WHERE id = '$idEsc'");
	}

	public function getMaterialVideos($materialId){
		$idEsc = mysqli_real_escape_string($this->koneksi, $materialId);
		$res = mysqli_query($this->koneksi, "SELECT * FROM material_videos WHERE material_id = '$idEsc' ORDER BY order_index ASC");
		$videos = [];
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$videos[] = $row;
			}
		}
		return $videos;
	}

	public function getMaterialVideoById($videoId){
		$id = (int) $videoId;
		$res = mysqli_query($this->koneksi, "SELECT * FROM material_videos WHERE id = $id LIMIT 1");
		return $res ? mysqli_fetch_assoc($res) : false;
	}

	public function addMaterialVideo($materialId, $title, $videoUrl, $script, $orderIndex){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO material_videos (material_id, title, video_url, script, order_index) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, "ssssi", $materialId, $title, $videoUrl, $script, $orderIndex);
		$res = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $res;
	}

	public function updateMaterialVideo($id, $title, $videoUrl, $script, $orderIndex){
		$stmt = mysqli_prepare($this->koneksi, "UPDATE material_videos SET title=?, video_url=?, script=?, order_index=? WHERE id=?");
		if (!$stmt) return false;
		$id = (int)$id;
		mysqli_stmt_bind_param($stmt, "sssii", $title, $videoUrl, $script, $orderIndex, $id);
		$res = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $res;
	}

	public function deleteMaterialVideo($id){
		$id = (int) $id;
		return mysqli_query($this->koneksi, "DELETE FROM material_videos WHERE id = $id");
	}

	// ====== Camera Practice History ======

	public function ensureCameraPracticeTable(){
		$sql = "CREATE TABLE IF NOT EXISTS camera_practice_history (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(6) NOT NULL,
			topic VARCHAR(255) NOT NULL,
			simulation_mode VARCHAR(60) NULL DEFAULT NULL,
			duration_seconds INT UNSIGNED NOT NULL,
			video_path VARCHAR(255) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_camera_user (user_id),
			CONSTRAINT fk_camera_user FOREIGN KEY (user_id) REFERENCES users(Id_User)
			ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		mysqli_query($this->koneksi, $sql);
	}

	public function saveCameraPracticeHistory($userId, $topic, $simulationMode, $durationSeconds, $videoPath){
		$stmt = mysqli_prepare($this->koneksi, "INSERT INTO camera_practice_history (user_id, topic, simulation_mode, duration_seconds, video_path) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		$durationSeconds = (int) $durationSeconds;
		mysqli_stmt_bind_param($stmt, "sssis", $userId, $topic, $simulationMode, $durationSeconds, $videoPath);
		$saved = mysqli_stmt_execute($stmt);
		$insertId = $saved ? mysqli_insert_id($this->koneksi) : false;
		mysqli_stmt_close($stmt);
		return $insertId;
	}

	public function getCameraPracticeHistory($userId, $limit = 10){
		$history = [];
		$limit = (int) $limit;
		$stmt = mysqli_prepare($this->koneksi, "SELECT id, topic, simulation_mode, duration_seconds, video_path, created_at FROM camera_practice_history WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
		if (!$stmt) return $history;
		mysqli_stmt_bind_param($stmt, "s", $userId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)){
			$history[] = $row;
		}
		mysqli_stmt_close($stmt);
		return $history;
	}

	public function handleSaveCameraPractice($currentUser){
		if (!$currentUser) {
			http_response_code(401);
			return ['status' => false, 'message' => 'Silakan login untuk menyimpan riwayat camera practice.'];
		}

		$topic = trim($_POST['topic'] ?? '');
		$simulationMode = trim($_POST['simulation_mode'] ?? '');
		$duration = (int) ($_POST['duration'] ?? 0);
		$maxVideoBytes = 100 * 1024 * 1024;

		if ($topic === '' || $duration <= 0 || $duration > 300 || empty($_FILES['video']['tmp_name'])) {
			http_response_code(400);
			return ['status' => false, 'message' => 'Data camera practice belum lengkap.'];
		}

		if ($_FILES['video']['error'] !== UPLOAD_ERR_OK) {
			http_response_code(400);
			return ['status' => false, 'message' => 'File video gagal diterima.'];
		}

		if (!is_uploaded_file($_FILES['video']['tmp_name']) || (int) $_FILES['video']['size'] <= 0 || (int) $_FILES['video']['size'] > $maxVideoBytes) {
			http_response_code(400);
			return ['status' => false, 'message' => 'Ukuran video tidak valid. Maksimal 100 MB.'];
		}

		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = $fileInfo ? finfo_file($fileInfo, $_FILES['video']['tmp_name']) : false;
		if ($fileInfo) finfo_close($fileInfo);
		if ($mimeType !== 'video/webm') {
			http_response_code(400);
			return ['status' => false, 'message' => 'Format video tidak didukung. Gunakan rekaman WebM dari aplikasi.'];
		}

		$uploadDir = __DIR__ . '/uploads/camera_video';
		if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
			http_response_code(500);
			return ['status' => false, 'message' => 'Folder penyimpanan video tidak bisa dibuat.'];
		}

		$fileName = $currentUser['Id_User'] . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.webm';
		$targetPath = $uploadDir . '/' . $fileName;
		$relativePath = 'uploads/camera_video/' . $fileName;

		if (!move_uploaded_file($_FILES['video']['tmp_name'], $targetPath)) {
			http_response_code(500);
			return ['status' => false, 'message' => 'Gagal menyimpan file video.'];
		}

		$practiceId = $this->saveCameraPracticeHistory(
			$currentUser['Id_User'],
			$topic,
			$simulationMode,
			$duration,
			$relativePath
		);

		if (!$practiceId) {
			if (is_file($targetPath)) unlink($targetPath);
			http_response_code(500);
			return ['status' => false, 'message' => 'Gagal menyimpan riwayat camera practice.'];
		}

		return [
			'status' => true,
			'message' => 'Riwayat camera practice berhasil disimpan.',
			'practice_history_id' => $practiceId,
			'item' => [
				'id' => $practiceId,
				'topic' => $topic,
				'simulation_mode' => $simulationMode,
				'duration_seconds' => $duration,
				'video_path' => $relativePath,
				'created_at' => date('Y-m-d H:i:s')
			]
		];
	}

}
