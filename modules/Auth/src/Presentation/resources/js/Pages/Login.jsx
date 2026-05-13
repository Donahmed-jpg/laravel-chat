import { useForm, Head } from '@inertiajs/react'

export default function Login({ errors: pageErrors = {} }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    })

    function submit(e) {
        e.preventDefault()
        post('/login')
    }

    return (
        <>
            <Head title="Login" />

            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div className="mb-8">
                            <h1 className="text-2xl font-bold text-gray-900">
                                Welcome back
                            </h1>
                            <p className="text-gray-500 mt-1 text-sm">
                                Sign in to your account
                            </p>
                        </div>

                        <form onSubmit={submit} className="space-y-5">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="john@example.com"
                                    autoFocus
                                />
                                {errors.email && (
                                    <p className="mt-1 text-xs text-red-600">{errors.email}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                />
                                {errors.password && (
                                    <p className="mt-1 text-xs text-red-600">{errors.password}</p>
                                )}
                            </div>

                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    checked={data.remember}
                                    onChange={e => setData('remember', e.target.checked)}
                                    className="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                />
                                <label htmlFor="remember" className="ml-2 text-sm text-gray-600">
                                    Remember me
                                </label>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-indigo-600 text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                {processing ? 'Signing in...' : 'Sign in'}
                            </button>
                        </form>

                        <p className="mt-6 text-center text-sm text-gray-500">
                            No account yet?{' '}
                            <a href="/register" className="text-indigo-600 hover:text-indigo-700 font-medium">
                                Create one
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </>
    )
}