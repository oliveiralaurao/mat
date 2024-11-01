// Definindo o conteúdo do cabeçalho e da barra de navegação
const navbarHTML = `
<header>
    <div class="search-container">
        <input type="text" placeholder="Search...">
        <button class="search-btn"><i class="fa fa-search"></i></button>
    </div>
    <div class="auth-buttons">
        ${isLoggedIn 
            ? `<button class="log-out"><a href="../manager/sair.php">Sair</a></button>` 
            : `<button class="sign-up"><a href="../../cadastrar.php">Sign Up</a></button>
               <button class="log-in"><a href="../../login.php">Log In</a></button>`
        }
    </div>
</header>

<nav class="nav-bar">
    <div class="sidebar-sticky">
        <h4 class="sidebar-heading">Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categorias.php">Categorias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="termos.php">Termos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usuarios.php">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../index.php">Site</a>
            </li>
        </ul>
    </div>
</nav>
`;

// Usando document.write para adicionar o conteúdo ao documento
document.write(navbarHTML);
