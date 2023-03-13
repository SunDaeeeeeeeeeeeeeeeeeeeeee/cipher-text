const form = document.getElementById('form');
const output = document.getElementById('output');
const encryptBtn = document.getElementById('encrypt-btn');

form.addEventListener('submit', async (event) => {
  event.preventDefault();

  output.textContent = 'Ciphering...';

  const file = document.getElementById('file').files[0];

  const formData = new FormData();
  formData.append('file', file);

  const response = await fetch('ciphering.php', {
    method: 'POST',
    body: formData
  });

  const data = await response.text();
  output.textContent = `The cipher text is ${data}`;
});
