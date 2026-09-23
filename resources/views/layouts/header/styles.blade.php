<style>
    :root {
        --ink: #1f2933;
        --muted: #667085;
        --paper: #fffdf8;
        --surface: #ffffff;
        --line: #eadfd2;
        --orange: #f26a3d;
        --orange-dark: #c94c24;
        --mint: #d8f2e6;
        --yellow: #f9c74f;
        --shadow: 0 18px 45px rgba(91, 62, 45, 0.1);
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        color: var(--ink);
        background: var(--paper);
        font-family: "Trebuchet MS", "Segoe UI", sans-serif;
    }

    a {
        color: inherit;
    }

    button,
    input,
    textarea {
        font: inherit;
    }

    .header {
        position: relative;
        z-index: 10;
        min-height: 224px;
        overflow: visible;
        background: url('/images/header.png') center / cover;
        border-bottom: 1px solid rgba(226, 196, 168, 0.7);
    }

    .header-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 24px;
        width: min(1240px, calc(100% - 48px));
        min-height: 86px;
        margin: 0 auto;
    }

    .logo {
        order: 1;
        color: var(--orange-dark);
        font-family: Georgia, serif;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: 0;
        text-decoration: none;
        white-space: nowrap;
    }


  .search-box {
    order: 2;
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 320px;
    max-width: 720px;
    height: 52px;
    padding: 4px;
    gap: 4px;
    background: var(--surface, #fff);
    border: 1px solid #e4d5c5;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(128, 82, 45, 0.08);
    transition: border-color 200ms ease, box-shadow 200ms ease;
  }

  .search-box:focus-within {
    border-color: var(--orange, #f26a3d);
    box-shadow: 0 0 0 4px rgba(242, 106, 61, 0.14);
  }

  /* --- Phần chọn tỉnh --- */
  .search-box .province-select {
    position: relative;
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 12px 0 14px;
    border-right: 1px solid #eee3d8;
    flex-shrink: 0;
  }

  .province-select .pin-icon {
    font-size: 16px;
    margin-right: 6px;
    opacity: 0.8;
  }

  .province-select select {
    appearance: none;
    -webkit-appearance: none;
    border: 0;
    outline: 0;
    background: transparent;
    font-size: 14px;
    font-weight: 500;
    color: var(--ink, #333);
    cursor: pointer;
    padding-right: 20px;
    max-width: 130px;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2398a2b3' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat: no-repeat;
    background-position: right 0 center;
  }

  /* --- Ô nhập liệu --- */
  .search-box input {
    flex: 1;
    min-width: 0;
    height: 100%;
    padding: 0 14px;
    color: var(--ink, #333);
    border: 0;
    outline: 0;
    background: transparent;
    font-size: 15px;
  }

  .search-box input::placeholder {
    color: #98a2b3;
  }

  /* --- Nút tìm kiếm --- */
  .search-box button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border: 0;
    border-radius: 12px;
    color: #fff;
    background: var(--orange, #f26a3d);
    cursor: pointer;
    font-size: 18px;
    transition: background 160ms ease, transform 160ms ease, box-shadow 160ms ease;
  }

  .search-box button:hover {
    background: var(--orange-dark, #e0552a);
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(242, 106, 61, 0.35);
  }

  .search-box button:active {
    transform: translateY(0);
    box-shadow: none;
  }

  @media (max-width: 560px) {
    .province-select select {
      max-width: 90px;
    }
    .search-box {
      min-width: 0;
    }
  }


    .header-actions {
        order: 3;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-left: auto;
    }

    .post-btn {
        display: inline-flex;
        align-items: center;
        min-height: 46px;
        padding: 0 18px;
        color: #fff;
        background: var(--orange);
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(242, 106, 61, 0.22);
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: transform 160ms ease, background 160ms ease;
    }

    .post-btn:hover {
        background: var(--orange-dark);
        transform: translateY(-2px);
    }

    .account {
        position: relative;
        padding-bottom: 10px;
    }

    .account-icon,
    .account-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        overflow: hidden;
        border: 2px solid #fff;
        border-radius: 50%;
        background: var(--mint);
        box-shadow: 0 4px 12px rgba(55, 93, 74, 0.14);
        cursor: pointer;
        font-size: 25px;
    }

    .account-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .account-menu {
        position: absolute;
        top: calc(100% - 2px);
        right: 0;
        display: none;
        width: 235px;
        overflow: hidden;
        padding: 8px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 14px;
        box-shadow: var(--shadow);
    }

    .admin-badge-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 44px;
        padding: 0 14px;
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(180, 83, 9, 0.1);
        transition: all 0.2s ease;
    }

    .admin-badge-btn:hover {
        background: #fde68a;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(180, 83, 9, 0.2);
    }

    .account:hover .account-menu,
    .account:focus-within .account-menu {
        display: block;
    }

    .account-name {
        padding: 10px 12px;
        color: var(--muted);
        border-bottom: 1px solid #f1e9e0;
        font-size: 13px;
        font-weight: 700;
    }

    .account-menu a,
    .account-menu button {
        display: block;
        width: 100%;
        padding: 11px 12px;
        color: var(--ink);
        border: 0;
        border-radius: 9px;
        background: transparent;
        font-size: 14px;
        text-align: left;
        text-decoration: none;
        cursor: pointer;
    }

    .account-menu a:hover,
    .account-menu button:hover {
        color: var(--orange-dark);
        background: #fff4ed;
    }

    .categories {
        order: 4;
        display: flex;
        flex: 0 0 100%;
        justify-content: flex-start;
        gap: 12px;
        width: 100%;
        margin: 0;
        padding: 14px 0 20px;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .category-item {
        flex: 0 0 82px;
        padding: 8px 5px;
        color: var(--ink);
        border: 1px solid transparent;
        border-radius: 14px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: transform 160ms ease, background 160ms ease, border-color 160ms ease;
    }

    .category-item:hover,
    .category-item.active {
        border-color: var(--orange);
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(242, 106, 61, 0.15);
    }

    .category-item.active span {
        color: var(--orange-dark);
        font-weight: 800;
    }

    .cat-icon-wrap {
        width: 48px;
        height: 48px;
        margin: 0 auto 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff4ed;
        border-radius: 50%;
    }

    .category-item img {
        width: 48px;
        height: 48px;
        margin: 0 auto 7px;
        object-fit: contain;
        filter: drop-shadow(0 4px 5px rgba(104, 75, 54, 0.12));
    }

    .header-fav-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e4d5c5;
        color: #ef4444;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .header-fav-btn:hover {
        background: #fff1f2;
        transform: scale(1.05);
    }

    .fav-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .header-chat-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e4d5c5;
        color: var(--orange);
        font-size: 19px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .header-chat-btn:hover {
        background: #fff4ed;
        transform: scale(1.05);
        color: var(--orange-dark);
    }

    .chat-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #f26a3d;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .category-item span {
        display: block;
        overflow: hidden;
        font-size: 12px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .auth-modal {
        display: none;
        position: fixed;
        inset: 0;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(31, 41, 51, 0.58);
        backdrop-filter: blur(5px);
        z-index: 9999;
    }

    .auth-box {
        position: relative;
        width: min(420px, 100%);
        padding: 34px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 22px;
        box-shadow: 0 25px 70px rgba(31, 41, 51, 0.22);
    }

    .auth-box h2 {
        margin: 0 0 10px;
        color: var(--ink);
        font-family: Georgia, serif;
        text-align: center;
    }

    .auth-description,
    .auth-switch {
        color: var(--muted);
        font-size: 14px;
        text-align: center;
    }

    .auth-description {
        margin: 0 0 24px;
    }

    .auth-box input,
    .auth-box textarea {
        width: 100%;
        margin-bottom: 13px;
        padding: 12px 14px;
        color: var(--ink);
        border: 1px solid var(--line);
        border-radius: 10px;
        outline: 0;
        background: #fffdfa;
    }

    .auth-box input {
        height: 45px;
    }

    .auth-box textarea {
        min-height: 90px;
        resize: vertical;
    }

    .auth-box input:focus,
    .auth-box textarea:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(242, 106, 61, 0.12);
    }

    .auth-submit {
        width: 100%;
        height: 46px;
        color: #fff;
        border: 0;
        border-radius: 10px;
        background: var(--orange);
        cursor: pointer;
        font-weight: 700;
    }

    .auth-submit:hover {
        background: var(--orange-dark);
    }

    .auth-close {
        position: absolute;
        top: 10px;
        right: 15px;
        width: 32px;
        height: 32px;
        color: var(--muted);
        border: 0;
        border-radius: 50%;
        background: transparent;
        cursor: pointer;
        font-size: 25px;
    }

    .auth-close:hover {
        color: var(--orange-dark);
        background: #fff4ed;
    }

    .auth-switch {
        margin: 19px 0 0;
    }

    .auth-switch a {
        color: var(--orange-dark);
        font-weight: 700;
        text-decoration: none;
    }

    .profile-avatar-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 110px;
        height: 110px;
        margin: 0 auto 16px;
        overflow: hidden;
        border-radius: 50%;
        background: var(--mint);
        font-size: 32px;
    }

    .profile-avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    main {
        flex: 1;
    }

    @media (max-width: 900px) {
        .header-container {
            flex-wrap: wrap;
            gap: 14px;
            padding: 17px 0 13px;
        }

        .search-box {
            order: 3;
            flex-basis: 100%;
            max-width: none;
        }

        .header {
            min-height: 270px;
        }
    }

    @media (max-width: 560px) {
        .header-container,
        .categories {
            width: min(100% - 28px, 1240px);
        }

        .logo {
            font-size: 23px;
        }

        .post-btn {
            min-height: 40px;
            padding: 0 12px;
            font-size: 13px;
        }

        .account-icon,
        .account-avatar {
            width: 40px;
            height: 40px;
        }

        .auth-box {
            padding: 28px 20px 22px;
        }
    }
</style>
