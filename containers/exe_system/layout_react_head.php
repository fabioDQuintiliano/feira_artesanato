<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $head_include; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Slab:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="ROOT/script/jquery-1.9.0.js"></script>

    <script>

        var ROOT = '<?php echo ROOT ?>';

    </script>

    <script src="ROOT/script/jquery-migrate-1.0.0.js"></script>
    <!-- Vue.js v3 -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.js"></script>
    <script src="<?php echo ROOT; ?>script/vue3-bridge.js"></script>
    <script src="ROOT/script/script_admin.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                container: {
                    center: true,
                    padding: '2rem',
                    screens: {
                        '2xl': '1400px',
                    },
                },
                extend: {
                    fontFamily: {
                        display: ['Roboto Slab', 'serif'],
                        heading: ['Roboto Slab', 'serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        border: "#ddbcbc",
                        input: "#ddbcbc",
                        ring: "#fe3403",
                        background: "#f7e3e3",
                        foreground: "#2e1717",
                        primary: {
                            DEFAULT: "#fe3403",
                            foreground: "#ffffff",
                        },
                        secondary: {
                            DEFAULT: "#f2cfcf",
                            foreground: "#2e1717",
                        },
                        muted: {
                            DEFAULT: "#ecd0d0",
                            foreground: "#745959",
                        },
                        accent: {
                            DEFAULT: "#007dc4",
                            foreground: "#ffffff",
                        },
                        card: {
                            DEFAULT: "#fff8f8",
                            foreground: "#2e1717",
                        },
                    },
                    borderRadius: {
                        lg: "0.75rem",
                        md: "calc(0.75rem - 2px)",
                        sm: "calc(0.75rem - 4px)",
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .text-gradient {
                @apply bg-clip-text text-transparent bg-gradient-to-r from-primary via-accent to-primary;
            }
            .glass-effect {
                @apply bg-card/80 backdrop-blur-lg border border-border/50;
            }
            .custom-scrollbar::-webkit-scrollbar {
                height: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                @apply bg-transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                @apply bg-muted/30 rounded-full hover:bg-muted/50 transition-colors;
            }
        }
        body {
            @apply bg-background text-foreground;
            color-scheme: light;
        }
        h1, h2, h3, h4, h5, h6 {
            @apply font-heading;
        }
    </style>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen">

    