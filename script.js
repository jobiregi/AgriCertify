// Simulated backend using localStorage
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
  
    if (registerForm) {
      registerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
  
        if (password !== confirmPassword) {
          alert('Passwords do not match!');
          return;
        }
  
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userExists = users.find(user => user.email === email);
  
        if (userExists) {
          alert('User already exists!');
          return;
        }
  
        users.push({ name, email, password });
        localStorage.setItem('users', JSON.stringify(users));
        alert('Registration successful!');
        window.location.href = 'login.html';
      });
    }
  
    if (loginForm) {
      loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
  
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const user = users.find(user => user.email === email && user.password === password);
  
        if (user) {
          alert(`Welcome back, ${user.name}!`);
          // Redirect to dashboard or homepage
        } else {
          alert('Invalid email or password!');
        }
      });
    }
  });
  