@extends('layouts.app')

@section('title', 'DevKei Solutions | School, HR, POS, stock and accounting software in Kenya')
@section('description', 'DevKei Solutions builds school management, HR, POS, stock and accounting systems for Kenyan institutions and businesses, plus custom development and integrations.')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <h1>Management systems built for how Kenyan schools and businesses actually run.</h1>
            <p class="hero-lead">DevKei Solutions builds school management, HR, POS, stock and accounting software, and connects it to the payment channels and systems you already use.</p>
            <div class="hero-actions">
                <a class="btn btn-amber" href="{{ route('demo.create') }}">Book a demo</a>
                <a class="btn btn-ghost-light" href="{{ route('quote.create') }}">Request a quote</a>
            </div>
            <p class="hero-note">5+ years of building and maintaining production systems.</p>
        </div>

        {{-- Sample product view. Replace with a real screenshot when ready. --}}
        <div class="panel" aria-label="Sample fee collection screen">
            <div class="panel-head"><span>Fee collection</span><small>Term 2</small></div>
            <div class="panel-stats">
                <div><b>KES 412,300</b><span>Collected today</span></div>
                <div><b>38</b><span>Balances due</span></div>
                <div><b>97%</b><span>Reconciled</span></div>
            </div>
            <ul class="panel-rows">
                <li style="--i:0"><span>Amina W.<small>Form 2 East</small></span><span>KES 18,500</span><span class="tag">Paid</span></li>
                <li style="--i:1"><span>Brian O.<small>Grade 6</small></span><span>KES 7,200</span><span class="tag tag-warn">Part paid</span></li>
                <li style="--i:2"><span>Grace M.<small>Form 4 West</small></span><span>KES 24,000</span><span class="tag">Paid</span></li>
                <li style="--i:3"><span>Kevin N.<small>Grade 8</small></span><span>KES 9,800</span><span class="tag tag-warn">Part paid</span></li>
            </ul>
            <p class="panel-foot">Sample data for illustration.</p>
        </div>
    </div>
</section>

<section class="facts">
    <div class="container facts-grid">
        <div><b>5+ years</b><span>Building production software</span></div>
        <div><b>Primary to university</b><span>School management at every level</span></div>
        <div><b>Built on Laravel</b><span>A stack we know and maintain</span></div>
        <div><b>Integrations</b><span>Payments, banks and your existing systems</span></div>
    </div>
</section>

<section class="section" id="products">
    <div class="container">
        <div class="section-head">
            <h2>Products ready to deploy</h2>
            <p>Each product is configured to your organisation, with your data migrated and your staff trained before go-live.</p>
        </div>

        @php
            $lead = $products->firstWhere('is_featured', true) ?? $products->first();
            $rest = $lead ? $products->where('id', '!=', $lead->id) : collect();
        @endphp

        @if($lead)
        <div class="products-layout">
            <article class="product-lead">
                <span class="cat">{{ ucfirst(str_replace('_', ' ', $lead->category)) }}</span>
                <h3>{{ $lead->name }}</h3>
                <p>{{ $lead->summary }}</p>
                @if($lead->features)
                    <ul class="check-list">
                        @foreach(array_slice($lead->features, 0, 4) as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="product-actions">
                    <a class="btn btn-primary" href="{{ route('products.show', $lead) }}">View product</a>
                    <a class="btn btn-outline" href="{{ route('demo.create', ['product' => $lead->id]) }}">Book a demo</a>
                </div>
            </article>

            <ul class="product-list">
                @foreach($rest as $product)
                <li>
                    <a class="product-row" href="{{ route('products.show', $product) }}">
                        <h3>{{ $product->name }}</h3>
                        <span class="row-cta">View</span>
                        <p>{{ $product->tagline }}</p>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @else
            <p class="empty">Products will appear here once they are added in the admin area.</p>
        @endif
    </div>
</section>

<section class="section section-alt" id="solutions">
    <div class="container">
        <div class="section-head">
            <h2>Solutions by sector</h2>
            <p>One team across education and business, so a school&rsquo;s fees, payroll and accounts can share the same data.</p>
        </div>
        <div class="split">
            <div>
                <h3>Education</h3>
                <dl class="defs">
                    <div><dt>Universities</dt><dd>Admissions, fees, timetables, exams and results for multi-faculty institutions.</dd></div>
                    <div><dt>Colleges</dt><dd>Course registration, fee tracking and transcripts for technical and vocational colleges.</dd></div>
                    <div><dt>Secondary schools</dt><dd>Class lists, exam analysis, fee balances and parent communication.</dd></div>
                    <div><dt>Primary schools</dt><dd>Competency-based assessment, attendance and clear fee statements.</dd></div>
                </dl>
            </div>
            <div>
                <h3>Business</h3>
                <dl class="defs">
                    <div><dt>HR and payroll</dt><dd>Staff records, leave, payroll and statutory deductions.</dd></div>
                    <div><dt>Point of sale</dt><dd>Fast checkout, receipts and daily cash-up across branches.</dd></div>
                    <div><dt>Stock management</dt><dd>Purchasing, transfers, stock-takes and reorder levels.</dd></div>
                    <div><dt>Accounting</dt><dd>Ledgers, VAT and multi-currency reporting.</dd></div>
                    <div><dt>Custom work</dt><dd>Customisations, data migration and integrations for systems you already run.</dd></div>
                </dl>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>From first call to go-live</h2>
            <p>A short, predictable process, with one person you can call throughout.</p>
        </div>
        <ol class="steps">
            <li><h3>Understand</h3><p>We learn how your organisation works today: fees, approvals, reports, and who needs what.</p></li>
            <li><h3>Configure</h3><p>The system is set up to match your structure, with customisations where it needs them.</p></li>
            <li><h3>Migrate and train</h3><p>Your existing records are imported and checked, and your staff are trained on real tasks.</p></li>
            <li><h3>Support</h3><p>After go-live we handle fixes, updates and new requirements as you grow.</p></li>
        </ol>
    </div>
</section>
@endsection
