<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: system-ui, sans-serif;
  }

  body {
    display: flex;
    background: #f5f5f5;
  }

  /* ===== HEADER ===== */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    height: 90px;
    width: 100%;
    padding: 12px 32px;
    background: #0e1e4d;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 1000;
  }

  .header-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .logo-header {
    width: 75px;
    height: 75px;
    object-fit: contain;
  }

  .brand-text {
    color: white;
    font-weight: 700;
    font-size: 32px;
    letter-spacing: 1px;
  }

  .notif {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
  }

  .icon-bell {
    width: 30px;
    height: 30px;
  }

  .search {
    width: 500px;
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.15) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff' viewBox='0 0 24 24'%3E%3Cpath d='M10 2a8 8 0 105.293 14.293l4.707 4.707 1.414-1.414-4.707-4.707A8 8 0 0010 2zm0 2a6 6 0 110 12 6 6 0 010-12z'/%3E%3C/svg%3E") no-repeat 18px center;
    background-size: 28px;
    padding-left: 60px;
    border: none;
    outline: none;
    border-radius: 25px;
    color: white;
    margin-left: -60px;
    font-size: 20px;
  }

  .search::placeholder {
    color: #c9cbda;
  }

  .user-area {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
  }

  .user-info {
    color: white;
    font-size: 18px;
  }

  /* ===== SIDEBAR ===== */
  .sidebar {
    width: 260px;
    background: #1c407a;
    color: white;
    padding: 140px 24px 24px;
    min-height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
  }

  .sidebar ul {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .sidebar ul li {
    list-style: none;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
  }

  .sidebar ul li.active,
  .sidebar ul li:hover {
    background: #476097;
  }

  .menu-item {
    display: flex;
    height: 50px;
    align-items: center;
    gap: 10px;
    padding: 29px 29px;
    border-radius: 29px;
    background: rgba(255, 255, 255, 0.08);
    cursor: pointer;
    transition: 0.25s;
    font-size: 18px;
  }

  .menu-item a {
    text-decoration: none;
    color: inherit;
    display: flex;
    height: 50px;
    align-items: center;
    transition: 0.25s;
    font-size: 18px;
    gap: 10px;
  }

  .menu-item.active {
    background: #d2a06b !important;
    color: #ffffff;
    font-weight: 600;
  }

  .menu-item.active .icon {
    fill: #ffffff;
  }

  .menu-item:hover:not(.active) {
    background: rgba(255, 255, 255, 0.18);
  }

  .icon {
    width: 22px;
    height: 22px;
    fill: white;
  }

  main {
    flex: 1;
    margin-left: 260px;
    min-height: 100vh;
    padding: 120px 40px 40px;
  }

  .admin-page-title {
    color: #10204f;
    font-size: 36px;
    margin-bottom: 8px;
  }

  .admin-page-subtitle {
    color: #667085;
    font-size: 18px;
    margin-bottom: 24px;
  }

  .admin-panel {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    box-shadow: 0 12px 32px rgb(16 32 79 / 0.07);
    padding: 24px;
  }

  .admin-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }

  .admin-card {
    background: #f8fafc;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    padding: 18px;
  }

  .admin-card h2,
  .admin-card h3 {
    color: #10204f;
    margin-bottom: 8px;
  }

  .admin-card p {
    color: #667085;
    line-height: 1.5;
  }

  .admin-button {
    background: #10204f;
    border-radius: 8px;
    color: #fff;
    display: inline-block;
    font-weight: 700;
    margin-top: 16px;
    padding: 12px 16px;
    text-decoration: none;
  }
</style>
