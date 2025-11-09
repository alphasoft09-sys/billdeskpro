<!DOCTYPE html>
<html>
<head>
    <title>Debug Authentication</title>
</head>
<body>
    <h1>Debug Authentication</h1>
    <div id="debug-info"></div>
    
    <script>
        function debugAuth() {
            const token = localStorage.getItem('auth_token');
            const debugDiv = document.getElementById('debug-info');
            
            debugDiv.innerHTML = `
                <p><strong>Token:</strong> ${token || 'No token found'}</p>
                <p><strong>Token Length:</strong> ${token ? token.length : 0}</p>
            `;
            
            if (token) {
                fetch('/api/profile', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    debugDiv.innerHTML += `
                        <p><strong>Response Status:</strong> ${response.status}</p>
                        <p><strong>Response OK:</strong> ${response.ok}</p>
                    `;
                    return response.json();
                })
                .then(data => {
                    debugDiv.innerHTML += `
                        <p><strong>Response Data:</strong></p>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    `;
                })
                .catch(error => {
                    debugDiv.innerHTML += `
                        <p><strong>Error:</strong> ${error.message}</p>
                    `;
                });
            }
        }
        
        document.addEventListener('DOMContentLoaded', debugAuth);
    </script>
</body>
</html>
