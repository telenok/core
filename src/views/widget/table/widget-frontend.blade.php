
<table class="table table-striped table-bordered" style="padding: 0; margin: 0;">
    <tbody>
        @foreach($rows as $r)
        <tr>
            @foreach($r as $c)
            <td data-container-id="{{$c['container_id']}}">
                
                @foreach($c['content'] as $content)
                {!! $content !!}
                @endforeach

            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>