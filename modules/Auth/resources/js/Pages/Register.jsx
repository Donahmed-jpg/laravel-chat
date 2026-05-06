import { useForm, Head } from '@inertiajs/react'

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    // console.log("inertia props:", props);

    function submit(e) {
        e.preventDefault()
        post('/register', {
            onError: () => reset('password', 'password_confirmation'),

            // onBefore: () => console.log('Starting request...'),
            // onSuccess: () => console.log('Success!'),
            // onError: (errors) => console.log('Server returned errors:', errors),
            // onFinish: () => console.log('Request finished.'),
        })
    }

    // Collect any errors that do not belong to a specific field
    // e.g. a general "something went wrong" error from the server
    const knownFields = ['name', 'email', 'password', 'password_confirmation']
    const generalErrors = Object.entries(errors).filter(
        ([key]) => !knownFields.includes(key)
    )

    return (
        <>
            <Head title="Register" />

            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

                        <div className="mb-8">
                            <h1 className="text-2xl font-bold text-gray-900">
                                Create an account
                            </h1>
                            <p className="text-gray-500 mt-1 text-sm">
                                Start chatting in seconds
                            </p>
                        </div>

                        {/* General server errors not tied to a specific field */}
                        {generalErrors.length > 0 && (
                            <div className="mb-5 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                                {generalErrors.map(([key, message]) => (
                                    <p key={key} className="text-sm text-red-600">
                                        {message}
                                    </p>
                                ))}
                            </div>
                        )}

                        <form onSubmit={submit} className="space-y-5">

                            <Field label="Name" error={errors.name}>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    placeholder="John Doe"
                                    autoFocus
                                    className={inputClass(errors.name)}
                                />
                            </Field>

                            <Field label="Email" error={errors.email}>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    placeholder="john@example.com"
                                    className={inputClass(errors.email)}
                                />
                            </Field>

                            <Field label="Password" error={errors.password}>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="min. 8 characters"
                                    className={inputClass(errors.password)}
                                />
                            </Field>

                            <Field label="Confirm Password" error={errors.password_confirmation}>
                                <input
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={e => setData('password_confirmation', e.target.value)}
                                    className={inputClass(errors.password_confirmation)}
                                />
                            </Field>

                            <SubmitButton processing={processing}>
                                Create account
                            </SubmitButton>

                        </form>

                        <p className="mt-6 text-center text-sm text-gray-500">
                            Already have an account?{' '}
                            <a href="/login" className="text-indigo-600 hover:text-indigo-700 font-medium">
                                Sign in
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </>
    )
}

function Field({ label, error, children }) {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
                {label}
            </label>
            {children}
            {error && (
                <p className="mt-1 text-xs text-red-600">{error}</p>
            )}
        </div>
    )
}

function SubmitButton({ processing, children }) {
    return (
        <button
            type="submit"
            disabled={processing}
            className="w-full bg-indigo-600 text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            {processing ? 'Please wait...' : children}
        </button>
    )
}

function inputClass(error) {
    const base = 'w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-offset-0 transition-colors'
    const normal = 'border-gray-300 focus:ring-indigo-500 focus:border-transparent'
    const invalid = 'border-red-400 focus:ring-red-500 focus:border-transparent bg-red-50'
    return `${base} ${error ? invalid : normal}`
}