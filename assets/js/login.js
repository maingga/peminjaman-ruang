function togglePassword() {
  const input = document.getElementById('password');
  input.type = input.type === 'password' ? 'text' : 'password';
}

function handleSubmit() {
  document.getElementById("loginBtn").disabled = true;
  document.getElementById("btnText").textContent = "Memproses...";
  document.getElementById("spinner").classList.remove("hidden");
  return true;
}
