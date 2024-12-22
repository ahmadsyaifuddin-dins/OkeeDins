<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Administrator</title>

    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">

</head>

<body>

</body>

</html>

<body>
    <div class="flex
        flex-col justify-center items-center bg-white h-[100vh]">
        <div
            class="mx-auto flex w-full flex-col justify-center px-5 pt-0 md:h-[unset] md:max-w-[50%] lg:h-[100vh] min-h-[100vh] lg:max-w-[50%] lg:px-6">

            <div
                class="my-auto mb-auto mt-8 flex flex-col md:mt-[70px] w-[350px] max-w-[450px] mx-auto md:max-w-[450px] lg:mt-[130px] lg:max-w-[450px]">
                <p class="text-[32px] font-bold text-zinc-950 dark:text-white">Admin Sign In</p>
                <p class="mb-2.5 mt-2.5 font-normal text-zinc-950 dark:text-zinc-400">Masukan email Admin dan
                    Password untuk masuk!</p>
                <hr>
                <br>
                <div>
                    <form action="{{ route('admin.authenticate') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="grid gap-2">
                            <div class="grid gap-1">
                                <label class="text-zinc-950 dark:text-white" for="email">Email</label>
                                <input
                                    class="mr-2.5 mb-2 h-full min-h-[44px] w-full rounded-lg border border-zinc-200 bg-white px-4 py-3 text-sm font-medium text-zinc-950 placeholder:text-zinc-400 focus:outline-0 dark:border-zinc-800 dark:bg-transparent dark:text-white dark:placeholder:text-zinc-400 @error('email') border-red-500 @enderror"
                                    id="email" placeholder="admin@example.com" type="email" name="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <label class="text-zinc-950 mt-2 dark:text-white" for="password">Password</label>
                                <input id="password" placeholder="Password" type="password" name="password" required
                                    class="mr-2.5 mb-2 h-full min-h-[44px] w-full rounded-lg border border-zinc-200 bg-white px-4 py-3 text-sm font-medium text-zinc-950 placeholder:text-zinc-400 focus:outline-0 dark:border-zinc-800 dark:bg-transparent dark:text-white dark:placeholder:text-zinc-400 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <button
                                class="whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 mt-2 flex h-[unset] w-full items-center justify-center rounded-lg px-4 py-4 text-sm font-medium"
                                type="submit">Sign in</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


    </div>
</body>
