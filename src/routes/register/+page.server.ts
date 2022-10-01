import type { Actions } from "./$types";
import { redirect } from "@sveltejs/kit";

export const actions: Actions = {
    default: async ({ request, cookies }) => {
        const data = await request.formData();
        const email = data.get("email");
        const password = data.get("password");
        const username = data.get("username");
        const tag = data.get("tag");

        if (!email || !password || !username || !tag) 
            return {
                status: 400,
                body: {
                    error: "Missing required fields",
                },
            };
        

        let response = await fetch("http://localhost:8000/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                email,
                password,
                username,
                tag,
            }),
        });

        let body = await response.json();

        if (response.status !== 200) {
            return {
                status: response.status,
                body,
            };
        }
        
        response = await fetch("http://localhost:8000/token", {
            method: "POST",
            headers: {
                "Content-Type": 'application/x-www-form-urlencoded'
            },
            body: `grant_type=&username=${email}&password=${password}&scope=&client_id=&client_secret=`,
        });

        body = await response.json();

        if (response.status !== 200) {
            return {
                status: response.status,
                body,
            };
        }

        cookies.set("token", body.access_token, {
            expires: new Date(Date.now() + 1000 * 60 * 60 * 24),
            path: "/",
            httpOnly: true,
            secure: true,
            sameSite: "strict"
        });

        throw redirect(303 ,"/");
    }
};