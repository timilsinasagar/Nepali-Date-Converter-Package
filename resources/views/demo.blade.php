{{-- resources/views/demo.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nepali Date Converter Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }

        .np-text {
            font-size: 1.1rem;
        }

        .today-badge {
            background: #eef2ff;
            border: 1px solid #dbe4ff;
        }
    </style>
</head>

<body>

    <div class="container py-5" style="max-width: 780px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Nepali Date Converter</h1>
            <span class="badge today-badge text-dark px-3 py-2">
                Today: {{ $today['formatted_en'] }} ({{ $today['weekday_en'] }})
            </span>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('nepali-date.demo.convert') }}" class="row g-3">
                    @csrf

                    <div class="col-md-5">
                        <label for="direction" class="form-label fw-semibold">Direction</label>
                        <select name="direction" id="direction" class="form-select">
                            <option value="ad_to_bs" @selected(old('direction', session('input_direction')) === 'ad_to_bs')>
                                AD &rarr; BS
                            </option>
                            <option value="bs_to_ad" @selected(old('direction', session('input_direction')) === 'bs_to_ad')>
                                BS &rarr; AD
                            </option>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label for="date_value" class="form-label fw-semibold" id="dateLabel">Date</label>
                        <input
                            type="text"
                            name="date_value"
                            id="date_value"
                            class="form-control @error('date_value') is-invalid @enderror"
                            placeholder="2026-07-08 or 2083-03-24"
                            value="{{ old('date_value', session('input_value')) }}"
                        >
                        <div class="form-text" id="dateHelp">
                            AD format: YYYY-MM-DD &middot; BS format: YYYY-MM-DD, २०८२-०३-२४, or "24 Ashadh 2082" / "24 असार 2082"
                        </div>
                        @error('date_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Convert</button>
                    </div>
                </form>

            </div>
        </div>

        @if(session('result'))
            @php($result = session('result'))
            <div class="card shadow-sm mt-4 border-success-subtle">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Result</h2>

                    <div class="row g-3">
                        @if($result['direction'] === 'ad_to_bs')
                            <div class="col-sm-6">
                                <div class="text-muted small">AD Input</div>
                                <div class="fw-semibold">{{ $result['input'] }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">BS Date</div>
                                <div class="fw-semibold">{{ $result['bs_date'] }} <span class="np-text">({{ $result['bs_date_np'] }})</span></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">Formatted</div>
                                <div class="fw-semibold">{{ $result['formatted_en'] }}</div>
                                <div class="np-text">{{ $result['formatted_np'] }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">Weekday</div>
                                <div class="fw-semibold">{{ $result['weekday_en'] }} <span class="np-text">({{ $result['weekday_np'] }})</span></div>
                            </div>
                        @else
                            <div class="col-sm-6">
                                <div class="text-muted small">BS Input</div>
                                <div class="fw-semibold">{{ $result['input'] }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">AD Date</div>
                                <div class="fw-semibold">{{ $result['ad_date'] }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">Formatted (BS)</div>
                                <div class="fw-semibold">{{ $result['formatted_en'] }}</div>
                                <div class="np-text">{{ $result['formatted_np'] }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small">Weekday</div>
                                <div class="fw-semibold">{{ $result['weekday_en'] }} <span class="np-text">({{ $result['weekday_np'] }})</span></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script>
        const direction = document.getElementById('direction');
        const dateLabel = document.getElementById('dateLabel');
        const dateInput = document.getElementById('date_value');
        const dateHelp = document.getElementById('dateHelp');

        function syncFieldForDirection() {
            if (direction.value === 'bs_to_ad') {
                dateLabel.textContent = 'BS Date';
                dateInput.placeholder = '2083-03-24';
                dateHelp.textContent = 'Supports: YYYY-MM-DD, २०८२-०३-२४, or "24 Ashadh 2082" / "24 असार 2082".';
            } else {
                dateLabel.textContent = 'AD Date';
                dateInput.placeholder = '2026-07-08';
                dateHelp.textContent = 'Format: YYYY-MM-DD.';
            }
        }

        syncFieldForDirection();
        direction.addEventListener('change', syncFieldForDirection);
    </script>

</body>

</html>
