@if(count($fontsToLoad) > 0)
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(in_array('Alexandria', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    @endif
    @if(in_array('Lemonada', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@300..700&display=swap" rel="stylesheet">
    @endif
    @if(in_array('Marhey', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Marhey:wght@300..700&display=swap" rel="stylesheet">
    @endif
    @if(in_array('Poppins', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
              rel="stylesheet">
    @endif
    @if(in_array('Roboto', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    @endif
    @if(in_array('Zain', $fontsToLoad))
        <link href="https://fonts.googleapis.com/css2?family=Zain:ital,wght@0,200;0,300;0,400;0,700;0,800;0,900;1,300;1,400&display=swap" rel="stylesheet">
    @endif
@endif
