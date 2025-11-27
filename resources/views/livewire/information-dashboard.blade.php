<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="table-responsive">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th>Description</th>
              <th>Download</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($downloads as $download)
                <tr>
                <td>{{ $download->description }}</td>
                <td>
                    <a href="{{ url("/storage/{$download->file_path}") }}" target="_blank" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download" aria-hidden="true"></i></a>
                </td>
                </tr>
            @empty
                <tr>
                <td colspan="2" class="text-center">No data available</td>
                </tr>
            @endforelse
        
          </tbody>
        </table>

        
      </div>
</div>
