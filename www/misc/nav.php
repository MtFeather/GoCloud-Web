<nav class="navbar navbar-default navbar-pf" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">
      <img src="assets/img/brand.svg" alt="PatternFly Enterprise Application" />
    </a>
  </div>
  <div class="collapse navbar-collapse navbar-collapse-1">
    <ul class="nav navbar-nav navbar-utility">
      
      <li class="dropdown">
        <button class="btn btn-link nav-item-iconic" id="horizontalDropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <span title="Help" class="fa pficon-help dropdown-title"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="horizontalDropdownMenu1">
          <li><a href="#0">Help</a></li>
          <li><a href="#0">About</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
          <span class="pficon pficon-user"></span>
          <span class="dropdown-title">
            <?php echo $_SESSION['user_name']; ?> <b class="caret"></b>
          </span>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a href="#0">個人資料</a>
          </li>
          <li>
            <a href="logout.php">登出</a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-primary">
      <li <?php echo ($title == 'Virtual Machine') ? 'class="active"' : NULL; ?>>
        <a href="vm.php">Virtual Machine</a>
      </li>
      <li <?php echo ($title == 'Container') ? 'class="active"' : NULL; ?>>
        <a href="container.php">Container</a>
      </li>
      <li <?php echo ($title == 'Restore') ? 'class="active"' : NULL; ?>>
        <a href="vm_restore.php">虛擬機還原</a>
      </li>
    </ul>
    </ul>
  </div>
</nav>
