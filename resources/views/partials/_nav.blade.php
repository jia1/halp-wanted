<div class="top-right links">
    <div class="nav-external" style="display: none;">
        <button id = "login" class="loginBtn loginBtn-facebook">
  			Login with Facebook
		</button>
    </div>

    <div class="nav-internal" style="display: none;">
    <nav>
  <ul>
    <li>
      <a href="{{ url('home') }}">HOME</a>
    </li>
    <li>
      <a href="#">TRENDING REQUESTS</a>
    </li>
    <li>
      <a href="{{ url('ask') }}">GET ADVICE</a>
    </li>
    <li>
      <a href="{{ url('me') }}">MY PROFILE </a>
    </li>
    <li>
      <a class="logout" onclick = "popUpWindow()">LOGOUT</a>
    </li>
  </ul>
</nav>
</div>

<script>
function popUpWindow(){
  alert("You have successfully logged out!");
}

</script>

<style>

@import url(https://fonts.googleapis.com/css?family=Open+Sans);

nav {
  max-width: 100%;
  mask-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, #ffffff 25%, #ffffff 75%, rgba(255, 255, 255, 0) 100%);
  margin: 0 auto;
  padding: 60px 0;
}

nav ul {
  text-align: center;
  background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 25%, rgba(255, 255, 255, 0.2) 75%, rgba(255, 255, 255, 0) 100%);
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.1), inset 0 0 1px rgba(255, 255, 255, 0.6);
}

nav ul li {
  display: inline-block;
}

nav ul li a {
  outline:0;
  border: 0;
  padding: 18px;
  font-family: "Open Sans";
  color: white;
  font-size: 18px;
  text-decoration: none;
  display: block;
}

nav ul li a:hover {
  color: rgba(0, 35, 122, 0.7);
}



body { padding: 2em; }


/* Shared */
.loginBtn {
	float:right;
  top: 10px;
  right: 10px;
  box-sizing: border-box;
  position: relative;
  /* width: 13em;  - apply for fixed size */
  margin: 0.2em;
  padding: 0 15px 0 46px;
  border: none;
  text-align: left;
  line-height: 34px;
  white-space: nowrap;
  border-radius: 0.2em;
  font-size: 16px;
  color: #FFF;
}
.loginBtn:before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  top: 0;
  left: 0;
  width: 34px;
  height: 100%;
}
.loginBtn:focus {
  outline: none;
}
.loginBtn:active {
  box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
}


/* Facebook */
.loginBtn-facebook {
  background-color: #4C69BA;
  background-image: linear-gradient(#4C69BA, #3B55A0);
  /*font-family: "Helvetica neue", Helvetica Neue, Helvetica, Arial, sans-serif;*/
  text-shadow: 0 -1px 0 #354C8C;
}
.loginBtn-facebook:before {
  border-right: #364e92 1px solid;
  background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_facebook.png') 6px 6px no-repeat;
}
.loginBtn-facebook:hover,
.loginBtn-facebook:focus {
  background-color: #5B7BD5;
  background-image: linear-gradient(#5B7BD5, #4864B1);
}


</style>