<style>
  .body{background: linear-gradient(#eee, #ccf)}
  .login{
    margin-left:auto;
    margin-right:auto;
    max-width: 500px;
    border: solid 1px #ccc;
    border-radius: 15px;
    padding: 15px;
    /* background: white; */
    margin-top: 15px;

  }
  .logo{
    width: 150px;
  }

  .login-input{background: #eef}
  .judul-sim{
    font-weight:bold;
    letter-spacing: 1px;
    font-size: 20px
  }
</style>
<div class="login text-center gradasi-hijau">
  <h1>LOGIN</h1>
  <div>
    <img class='logo' src="assets/img/logo-login.png">
  </div>
  <div class="judul-sim mb-4 mt-2">
    <script>
      let t = 'SIM KOS PELANGI YES';
      let r = null;
      let warna = [
        'red',
        'green',
        'blue',
        'violet',
        'orange',
        'purple'
      ];
      for (let i = 0; i < t.length; i++) {
        r = parseInt(Math.random()*6);
        document.write(`<span style="color: ${warna[r]}">${t.charAt(i)}</span>`);
      }
    </script>
  </div>
  
  <div class="wadah text-left login-input">
    <input type="text" class="form-control mb-2" placeholder="username" name="username">
    <input type="text" class="form-control mb-2" placeholder="password" name="password">
    <button class="btn btn-primary btn-block">Login</button>
  </div>

</div>