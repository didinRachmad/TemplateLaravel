<div>
    <h4>Item: <span class="fw-bold text-primary">{{ $item->kode_item }} - {{ $item->nama_item }}</span></h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Data Lama</th>
                    <th>Data Baru</th>
                </tr>
            </thead>
            <tbody>
                @if ($activities->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data.</td>
                    </tr>
                @else
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if (isset($activity->properties['old_data']) && is_array($activity->properties['old_data']))
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Nilai Lama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activity->properties['old_data'] as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>
                                                        @if (is_array($value))
                                                            {{ json_encode($value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if (isset($activity->properties['new_data']) && is_array($activity->properties['new_data']))
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Nilai Baru</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activity->properties['new_data'] as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>
                                                        @if (is_array($value))
                                                            {{ json_encode($value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
