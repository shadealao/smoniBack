@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('images/logo.png') }}" alt="SMONI Logo" style="height: 50px;">
            <h1 style="color: #2d3748; margin-top: 10px;">SMONI</h1>
            <p style="color: #718096; margin: 0;">AUTO-MOTO ECOLE</p>
        @endcomponent
    @endslot

    # Bienvenue sur SMONI

    Merci pour votre inscription !  
    Pour finaliser la création de votre compte, cliquez sur le lien ci-dessous :

    @component('mail::button', ['url' => $verificationUrl, 'color' => 'primary'])
        Finaliser mon inscription
    @endcomponent

    Si vous n'êtes pas à l'origine de cette inscription, vous pouvez simplement ignorer ce message.  
    À très vite sur SMONI !

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} L'équipe SMONI. Tous droits réservés.
        @endcomponent
    @endslot
@endcomponent