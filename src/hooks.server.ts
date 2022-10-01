import type { Handle } from "@sveltejs/kit";

export const handle: Handle = async ({event, resolve}) => {
    if(event.url.pathname !== ("/login" || "/register")) {
        const token = event.cookies.get("token");
        if (token) {
            const resp = await fetch("http://localhost:8000/user/me", {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`
                },
            });
            
            if(resp.status === 200) {
                const response = await resolve(event);
                return response;
            }
        }
        else 
            return new Response(null, {
            status: 302,
            headers: {
                Location: "/login"
            }
        });
    }
    const response = await resolve(event);
    return response;
}