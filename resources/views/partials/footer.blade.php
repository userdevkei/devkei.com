<section class="cta-band">
    <div class="container cta-inner">
        <div>
            <h2>See it running on your own data.</h2>
            <p>Book a walkthrough, or describe what you need and get a written quote.</p>
        </div>
        <div class="cta-actions">
            <a class="btn btn-amber" href="{{ route('demo.create') }}">Book a demo</a>
            <a class="btn btn-ghost-light" href="{{ route('quote.create') }}">Request a quote</a>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="{{ route('home') }}">@include('partials.logo')<span>DevKei</span></a>
                <p class="footer-about">Software development and systems integration for schools, retailers and finance teams across Kenya.</p>
            </div>
            <div>
                <h4>Products</h4>
                <ul>
                    <li><a href="{{ route('products.index') }}">All products</a></li>
                    <li><a href="{{ route('home') }}#solutions">School management</a></li>
                    <li><a href="{{ route('home') }}#solutions">HR, POS and stock</a></li>
                    <li><a href="{{ route('home') }}#solutions">Accounting</a></li>
                </ul>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('demo.create') }}">Book a demo</a></li>
                    <li><a href="{{ route('quote.create') }}">Request a quote</a></li>
                </ul>
            </div>
            <div>
                <h4>Get in touch</h4>
                <ul>
                    <li><a href="mailto:kitilionesmus27@gmail.com">kitilionesmus27@gmail.com</a></li>
                    <li><a href="tel:+254729434393">+254 729 434 393</a></li>
                    <li>Kitengela, Kenya</li>
                </ul>
            </div>
        </div>
        <div class="footer-base">
            <span>&copy; {{ date('Y') }} DevKei Solutions. All rights reserved.</span>
            <span>devkei.com</span>
        </div>
    </div>
</footer>
