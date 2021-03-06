@props([
    'actions' => null,
    'theme' => null,
    'row' => null
])
@if(isset($actions) && count($actions) && $row !== '')
    @foreach($actions as $key => $action)
        <td wire:key="action-{{ $key }}" class="pg-actions {{ $theme->table->tdBodyClass }}"
            style="{{ $theme->table->tdBodyStyle }}">
            <div class="w-full md:w-auto">
                @php
                    foreach ($action->param as $param => $value) {
                        if (!empty($row->{$value})) {
                            $parameters[$param] = $row->{$value};
                        } else {
                            $parameters[$param] = $value;
                        }
                    }


                    switch(get_class($row)){
                        case "App\Models\Ingrediente":
                        case "App\Models\Receta":
                            $saltar = false;

                            if($action->action == "seed" && !auth()->user()->can('seeder_save')){
                                $saltar = true;
                            }

                            if($row->esPublico()){
                                if(!auth()->user()->can('public_edit') && $action->action == "edit"){
                                    $saltar = true;
                                }
                                if(!auth()->user()->can('public_destroy') && $action->action == "destroy"){
                                    $saltar = true;
                                }
                                if($action->action == "publish"){
                                    $saltar = true;
                                }
                            }
                            else{
                                if(auth()->user()->id != $row->user_id){
                                    $saltar = true;
                                }
                                else{
                                    if(!$row->esPublicable() && $action->action == "publish"){
                                        $saltar = true;
                                    }
                                }
                            
                            break;
                        }
                    }

                @endphp
                
                @if($saltar)
                    @continue
                @endif

                @if(filled($action->event) || filled($action->view))
                    <a  @if($action->event) wire:click='$emit("{{ $action->event }}", @json($parameters))'
                        @endif
                        @if($action->view) wire:click='$emit("openModal", "{{$action->view}}", @json($parameters))'
                        @endif
                        class="{{ filled($action->class) ? $action->class : $theme->actions->headerBtnClass }}">
                        {!! $action->caption !!}
                    </a>
                @endif

                @if(filled($action->route))
                    @if(strtolower($action->method) !== 'get')
                        <form target="{{ $action->target }}"
                            action="{{ route($action->route, $parameters) }}"
                            method="post">
                            @method($action->method)
                            @csrf
                            <button type="submit"
                                @if($action->action == "destroy")
                                onclick="confirmarBorrado(event);"
                                @endif
                                class="{{ filled( $action->class) ? $action->class : $theme->actions->headerBtnClass }}">
                                {!! $action->caption ?? '' !!}
                            </button>
                        </form>
                    @else
                        <a href="{{ route($action->route, $parameters) }}"
                            target="{{ $action->target }}"
                            class="{{ filled($action->class) ? $action->class : $theme->actions->headerBtnClass }}">
                            {!! $action->caption !!}
                        </a>
                    @endif
                @endif
            </div>
        </td>
    @endforeach
@endif
