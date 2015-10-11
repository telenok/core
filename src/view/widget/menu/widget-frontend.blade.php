
@foreach($nodeIds as $idLevel1)

    @if (is_array($idLevel1))

        @foreach($idLevel1 as $idLeve2)

            @if (is_array($idLeve2))

                @foreach($idLevel2 as $idLeve3)

                    @if (is_array($idLeve3))


                    @else

                        @if ($page = $pages->find($idLeve3))

                        @endif

                    @endif

                @endforeach
            
            @else
            
                @if ($page = $pages->find($idLeve2))

                @endif

            @endif

        @endforeach

    @else

        @if ($page = $pages->find($idLevel1))

        @endif

    @endif

@endforeach