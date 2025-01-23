<div>
    @if($isLoading)
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Asset</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->nama }}</td>
                        <td>{{ $asset->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
