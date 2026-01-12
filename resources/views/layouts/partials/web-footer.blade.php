
<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h4 class="mb-3"><i class="bi bi-shop"></i> FoodHub</h4>
                <p>Your trusted partner for food delivery, daily meals, and catering services in Dar es Salaam.</p>
                <div class="social-links mt-3">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin fs-4"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <h5 class="mb-3">Company</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">About Us</a></li>
                    <li class="mb-2"><a href="#">Careers</a></li>
                    <li class="mb-2"><a href="#">Press</a></li>
                    <li class="mb-2"><a href="#">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h5 class="mb-3">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">Food Delivery</a></li>
                    <li class="mb-2"><a href="#">Daily Meals</a></li>
                    <li class="mb-2"><a href="#">Catering</a></li>
                    <li class="mb-2"><a href="#">Corporate</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h5 class="mb-3">Partners</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">Become a Restaurant Partner</a></li>
                    <li class="mb-2"><a href="#">Become a Delivery Partner</a></li>
                    <li class="mb-2"><a href="#">Partner Portal</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h5 class="mb-3">Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">Help Center</a></li>
                    <li class="mb-2"><a href="#">Contact Us</a></li>
                    <li class="mb-2"><a href="#">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; 2024 FoodHub. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> in Dar es Salaam</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple JavaScript for interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add to cart animation
        const addButtons = document.querySelectorAll('.menu-item-card .btn-outline-primary');
        addButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-success');
                
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-plus-lg"></i>';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                }, 1500);
            });
        });

        // Simulate location detection
        const locationInput = document.querySelector('input[placeholder*="delivery address"]');
        if (locationInput && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log('Location detected:', position.coords);
                },
                function(error) {
                    console.log('Location detection failed:', error);
                }
            );
        }
    });
</script>