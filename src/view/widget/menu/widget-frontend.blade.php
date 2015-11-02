
@foreach($nodeIds as $idLevel1)

    @if (is_array($idLevel1))

        @foreach($idLevel1 as $idLeve2)

            @if (is_array($idLeve2))

                @foreach($idLevel2 as $idLeve3)

                    @if (is_array($idLeve3))

                    @elseif ($item = $items->find($idLeve3))

                    @endif

                @endforeach
            
            @elseif ($item = $items->find($idLeve2))

            @endif

        @endforeach

    @elseif ($item = $items->find($idLevel1))
        {{ $item->translate('title') }}
    @endif

@endforeach