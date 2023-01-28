<div class="flex flex-wrap border-2 border-dashed border-blue-500 rounded-md mx-3 items-top justify-center">
    @if (!empty($team))
        @foreach ($team as $pokemon)
            <div class="border-2 border-sky-700 rounded-md pokemon h-fit flex flex-col m-3 p-3">
                <div class="flex  border-b-2 border-sky-700">
                    <div class="flex mx-auto flex-col border-r-2 border-sky-700 ">
                        <strong class="text-center text-xl uppercase">{{ $pokemon['name'] }}</strong>
                        <img src="{{ $pokemon['sprite'] }}" alt="Imagen del pokemón">
                    </div>
                    <div class="flex flex-col px-3">
                        <strong class="mb-2 mx-auto">STATS</strong>
                        <ul class="list-disc list-inside">
                            <li>height: {{ $pokemon['height'] * 10 }}cm</li>
                            <li>weight: {{ $pokemon['weight'] / 10 }}kg</li>
                            @foreach ($pokemon['stats'] as $stat)
                                <li>{{ $stat['name'] }}: {{ $stat['pivot']['base_stat'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col px-3 mt-3">
                    <strong class="mb-2 mx-auto cursor-pointer view-movements" data-id="{{ $pokemon['id'] }}">Ver
                        movimientos</strong>
                    <div class="hidden" id="moves-{{ $pokemon['id'] }}">
                        @foreach ($pokemon['moves'] as $move)
                            <div class="flex flex-col mb-2 border-2 border-sky-500 border-dotted rounded-md p-3">
                                <strong class="text-center">{{ $move['name'] }}</strong>
                                <div class="grid grid-cols-2">
                                    <span>damage_class: {{ $move['damage_class'] }}</span>
                                    <span>type: {{ $move['type'] }}</span>
                                    <span>power: {{ $move['power'] }}</span>
                                    <span>accuracy: {{ $move['accuracy'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <span class="text-center w-full text-xl p-3">No has agregado ningún pokemón a tu equipo</span>
    @endif
</div>
