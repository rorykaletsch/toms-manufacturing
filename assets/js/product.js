document.getElementById('admin-portal').addEventListener('click', function(){
    window.location.href = '/dealer-portal/pages/admin/admin.php'
});

document.getElementById('website').addEventListener('click', function(){
    window.location.href = '/dealer-portal/index.php'
});

function loadProductDetails() {
            const productId = document.getElementById('product_select').value;
            if (!productId) return;

            fetch(`/dealer-portal/pages/admin/update-product.php?product_id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('product_name').value = data.name;
                    document.getElementById('product_description').value = data.description;
                    document.getElementById('product_price').value = data.price;
                    document.getElementById('product_quantity').value = data.quantity;
                    document.getElementById('product_category').value = data.category;
                })
                .catch(error => console.error('Error fetching product details:', error));
        }