<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">

        <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Dosis&display=swap" rel="stylesheet">

        <style type="text/css">
            div.page {
                page-break-after: always;
                page-break-inside: avoid;
            }

            td, p, pre {
                font-family: 'Dosis', sans-serif;
                font-size: 20px;
            }

            .badge {
                font-size: 20px;
            }

            th, h1, h2, .badge {
                font-family: 'Courgette', cursive;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            @foreach($recipes as $recipe)
                <div class="row {{!$loop->last ? 'page' : ''}}">
                    <div class="col-12 mb-4">
                        <h1 class="display-4">{{$recipe->name}}</h1>
                    </div>

                    <div class="col-12 mb-4">
                        <table class="table text-center table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Añadida el</th>
                                    <th scope="col">Raciones</th>
                                    <th scope="col">Duración estimada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{\Jenssegers\Date\Date::parse($recipe->created_at)->format('j \\de F \\de Y')}}</td>
                                    <td>{{trans_choice('{1} :rations ración|[2, *] :rations raciones', $recipe->rations, ['rations' => $recipe->rations])}}</td>
                                    <td>{{$recipe->duration}} min.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 bg-light rounded mb-4">
                        <h2 class="display-6">Descripción</h2>
                        <p>{{$recipe->description}}</p>
                    </div>

                    <div class="col-12 rounded">
                        <h2 class="display-6">Ingredientes</h2>
                        <pre class="text-break">{{$recipe->ingredients}}</pre>
                    </div>

                    <div class="col-12 bg-light rounded">
                        <h2 class="display-6">Pasos</h2>
                        <pre class="text-break">{{$recipe->steps}}</pre>
                    </div>

                    <div class="col-12 rounded-pill">
                        <h2 class="display-5">Etiquetas</h2>
                        @foreach($recipe->tags as $tag)
                            <span class="badge badge-pill">{{$tag->name}}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>


        {{--        <div class="description">--}}
        {{--            <h3>Descripción</h3>--}}
        {{--            <p>{!! nl2br($recipe->description) !!}</p>--}}
        {{--        </div>--}}

        {{--        <div class="ingredients">--}}
        {{--            <h3>Ingredientes</h3>--}}
        {{--            <pre>{!! nl2br($recipe->ingredients) !!}</pre>--}}
        {{--        </div>--}}

        {{--        <div class="steps">--}}
        {{--            <h3>Pasos</h3>--}}
        {{--            <pre>{!! nl2br($recipe->steps) !!}</pre>--}}
        {{--        </div>--}}

        {{--        <div class="tags">--}}
        {{--            <h3>Etiquetas</h3>--}}
        {{--            <ul>--}}
        {{--                @foreach($recipe->tags as $tag)--}}
        {{--                    <li>{{$tag->name}}</li>--}}
        {{--                @endforeach--}}
        {{--            </ul>--}}
        {{--        </div>--}}


    </body>
</html>
